# Anarjo — Fix Plan

> Generated from a full-app audit. Baseline at time of audit:
> `php artisan test` → **112 passed** · `npm run build` → OK · `pint --test` → **28 files failing**.
>
> Severity legend: 🔴 Critical · 🟠 High · 🟡 Medium · 🟢 Low

Work top-to-bottom. Phases are ordered by risk and dependency.

---

## Phase 0 — Immediate security hygiene (do first, ~30 min)

- [ ] **Rotate exposed secrets.** `.env:60` / `.env.production:25,58` contain real SMTP (`anarjo%shofiq`) and DB (`anarjo5271`) passwords plus a real `APP_KEY`. Treat as compromised.
  - Change the SMTP mailbox password.
  - Change the production MySQL password.
  - `php artisan key:generate` on production (invalidates sessions/encrypted data — acceptable for a fresh deploy).
  - Ensure `.env`, `.env.production` are never served or committed (already gitignored; keep it that way).
- [ ] **Do not ship `APP_DEBUG=true`.** `.env:4` is `true`. Confirm production always uses `.env.production` (`APP_DEBUG=false`).
- [ ] Verify `.env.production` has all payment keys (`BKASH_*`, `SSLCOMMERZ_*`) — currently **missing**, which silently disables both gateways.

**Acceptance:** secrets rotated; no plaintext credentials in the project tree; production env file complete.

---

## Phase 1 — Critical correctness & payment security

### 1.1 Stationery order items (🔴)
- [ ] Fix `app/Livewire/Checkout.php:245-257` so stationery lines don't write a stationery id into `order_items.book_id` (FK → `books.id`, migration `2026_05_24_035160:14`).
  - **Preferred:** add `itemable_id` + `itemable_type` (morph) via new migration; keep `book_id` for back-compat or drop it.
  - **Minimal:** store `book_id = null` for stationery and resolve the product through `item_type`.
- [ ] Update `app/Models/OrderItem.php:19-22` — replace `book()` with a polymorphic `itemable()` (or add `stationery()` + a `product()` accessor).
- [ ] Update all consumers to use the resolver: `app/Models/Order.php:44-52`, `app/Services/SslCommerzService.php:44`, `resources/views/invoices/order.blade.php:249`, `frontend/orders/{confirmation,index,payment}.blade.php`.

**Acceptance:** placing an order containing a stationery item succeeds; invoice/confirmation show the correct product name; stock decrements/restores on the stationery row.

### 1.2 SSLCommerz amount-verification bypass (🔴)
- [ ] `app/Services/SslCommerzService.php:98-130`: when the `val_id` branch fails, **do not fall through** to the tran_id-only fallback. Return `false`, or make the fallback also verify returned `tran_id` **and** `amount`.
- [ ] `SslCommerzController.php:60,131`: reject callbacks that omit `amount`/`currency` instead of defaulting to `$order->total` (defeats tampering-by-omission).

**Acceptance:** a callback with a wrong amount, a `val_id` whose returned amount mismatches, or a missing `amount` field is rejected.

### 1.3 Public, CSRF-exempt order mutation (🔴)
- [ ] `bootstrap/app.php:38-44` + `routes/web.php:87,90-93`: load the SSLCommerz/bKash callbacks on POST-only where possible (`success`/`cancel`/`fail`).
- [ ] Add server-side authenticity checks on every callback (see 1.4) before mutating order state — treat the endpoint as hostile input.
- [ ] Ensure a callback can never move an order out of a terminal state (`completed` stays completed).

**Acceptance:** a forged GET/POST to `/payment/sslcommerz/cancel` with a known `invoice_no` cannot cancel an order unless it passes signature validation.

### 1.4 Callback authenticity & idempotency (🟠/🟡)
- [ ] Validate SSLCommerz `verify_sign`/`verify_key` (and/or confirm through the validation API before any state change).
- [ ] `BkashController.php:75`: compare amounts with a tolerance (`round`/epsilon), not strict float `===`.
- [ ] Wrap every payment status transition in `DB::transaction` + `Order::lockForUpdate()` to prevent double processing (IPN + browser redirect).
- [ ] `Order::restoreStock()` (`app/Models/Order.php:42-56`): guard against double-restore (already keyed on `stock_restored`, but confirm the flag is cast to bool — see 2.4).
- [ ] Stop flashing raw `$e->getMessage()` to users (`BkashController.php:41-44,101-103`); log instead.
- [ ] `BkashService.php:90`: don't blindly overwrite `payment_id` on re-initiation (avoid orphaning a prior paymentID).

**Acceptance:** forged callbacks rejected; concurrent IPN + redirect yields exactly one state transition and one stock restore.

### 1.5 Proxy / IP spoofing (🟠)
- [ ] `bootstrap/app.php:24-30`: replace `trustProxies(at: '*')` with a concrete list of trusted proxy IPs (or env-driven list).
- [ ] Confirm `throttle:*` limits work against real client IPs after the change.

**Acceptance:** `X-Forwarded-For` from an untrusted source no longer changes `request()->ip()`.

---

## Phase 2 — High-severity data & auth integrity

- [ ] **Email verification is a no-op (🟠).** `app/Models/User.php:12` doesn't implement `Illuminate\Contracts\Auth\MustVerifyEmail`, so `verified` middleware (`routes/web.php:108`, `routes/admin.php:37`) does nothing.
  - Either implement `MustVerifyEmail` (and keep the Breeze verification routes/tests) or remove the `verified` middleware to make intent explicit.
- [ ] **Admin deletes lose data / orphan rows (🟠).**
  - Add `SoftDeletes` + `deleted_at` migration to `Book`, `Blog`, `Author`, `Category`, `Event`, `Stationery` (and any other hard-deleted admin resource).
  - Add model `deleting` hooks to delete/soft-delete polymorphic `comments` and `likes` (`app/Livewire/Admin/Books.php:215`, `Blogs.php:50`).
- [ ] **`Order` missing casts (🟠).** `app/Models/Order.php`: add `casts()` for `stock_restored` (bool), `payment_status`/`delivery_status` (`PaymentStatus::class`/`DeliveryStatus::class`), and money columns. Audit call sites for the new enum objects.
- [ ] **Slug collisions → 500 (🟠).** In `BlogForm.php:79`, `EventForm.php:67`, `Categories.php:74`, `Brands.php:75`, `Authors.php:75`, `BlogTags.php:65`, `BlogCategories.php:69`, generate unique slugs (mirror the pattern already used in `Books.php:187-192`/`Stationeries.php:107-112`).
- [ ] **Persistent Livewire middleware (🟠).** Publish `config/livewire.php` and add `App\Http\Middleware\AdminMiddleware` to `persistent_middleware` so admin is re-checked on Livewire requests.
- [ ] **Gateways honored on initiation (🟡).** `SslCommerzController::initiate` / `BkashController::initiate`: enforce `pm_sslcommerz`/`pm_bkash` settings; `PaymentController::process` should only render enabled gateways and guard already-finalized orders.

---

## Phase 3 — Medium correctness

- [ ] **Settings cache key mismatch (🟡).** `database/migrations/2026_07_29_155932_remove_payment_credentials_from_settings.php:20` forgets `'settings'`; `SettingsService` uses `'site_settings'` (`app/Services/SettingsService.php:21`). Fix the key; consider `SettingsService::clearCache()` in seeders (`SettingsSeeder.php:32`) after direct writes.
- [ ] **Mail settings dead config (🟡).** Decide: either wire `app/Livewire/Admin/Settings.php:161-171` DB values into `config/mail.php` at runtime, or remove the email tab fields and stop persisting them.
- [ ] **`ProcessOrderJob` no-op (🟡).** `app/Jobs/ProcessOrderJob.php:23-24` generates then discards a PDF. Either store/send the invoice, or remove the job. Add `$tries`/`backoff`/`failed()`; with `QUEUE_CONNECTION=sync` a failure throws during checkout — make the job non-fatal to order creation.
- [ ] **Filesystem / media URLs (🟡).** `app/Models/Media.php:13-16` ignores the `disk` column. Use `Storage::disk($this->disk)->url($this->path)`. Align `FILESYSTEM_DISK` with `asset('storage/…')` expectations.
- [ ] **Pagination reset on filter/search (🟡).** Add `$this->resetPage()` in the `updated*`/search hooks across the ~19 admin components: Blogs, Orders, Reviews, Comments, Books, Categories, Brands, Authors, Events, BlogTags, BlogCategories, Manuscripts, MediaManager, AdminUsers, UserManagement, Stationeries, BookFairEditions, Coupons, Newsletter.
- [ ] **`WithPagination` missing (🟡).** `app/Livewire/Admin/HomeSettings.php:106`, `AboutSettings.php:76` call `paginate()` while their blades call `links()`.
- [ ] **Dropped field (🟡).** `app/Livewire/Admin/AboutSettings.php:57-67` never saves `about_mission_heading`.
- [ ] **Cart stock cap (🟡).** Enforce max qty vs stock in `app/Services/Cart/CartService.php:22-44` and `app/Livewire/AddToCartButton.php:38-51`.
- [ ] **Cart shipping rate (🟡).** `app/Livewire/Cart.php:83-87` should mirror `Checkout::isDhaka()`.
- [ ] **Reviews (🟡).** Require a purchase (or explicit product) before allowing a review; paginate approved reviews; add a unique `(user_id, book_id)` constraint migration.
- [ ] **bKash query endpoint (🟡).** `app/Services/BkashService.php:124` → `/tokenized/checkout/payment/status` (POST). Persist/refresh the token in cache with expiry instead of per-instance memory.
- [ ] **Gateway sandbox default (🟡).** `BkashService.php:30`, `SslCommerzService.php:25` should default to **live** in production (or fail loudly if the setting is missing).
- [ ] **Model cast/index gaps (🟡).** Add casts for `Book.price/sale_price/views`, `Stationery` money fields, `Coupon.value/min_amount/usage_count`, `Media.size`, `OrderItem.*`. Add indexes on `status` columns. Drop dead `books.category_id` (superseded by `book_category`).

---

## Phase 4 — Low / polish

- [ ] **Run the formatter.** `vendor/bin/pint` (currently 28 files failing).
- [ ] **Slug on update.** `Event.php:22`, `BookFairEdition.php:25` only generate slug on `creating`; regenerate on title change or keep stable intentionally.
- [ ] **Media cleanup.** `app/Livewire/Admin/MediaManager.php:85-88` `deleteMultiple()` is a stub; `UploadsMedia` never deletes replaced files (orphan files + rows).
- [ ] **Mobile coupon entry.** `resources/views/livewire/checkout.blade.php:155` coupon UI only in `hidden lg:block` sidebar.
- [ ] **Null-safety** on `$comment->user->name` / `$review->user->name` when a user was deleted.
- [ ] **Repo hygiene.** Untrack generated `storage/mpdf/**` (47 files); ensure `InvoiceService::tempDir` is writable/gitignored.
- [ ] **Dead code / duplicates.** `routes/web.php:36` `books.author` duplicates `author.show`; unused `$request` in `OrderController::confirmation`; `BkashService::queryPayment` unused; `config/dompdf.php` configured but unused (mPDF is used).
- [ ] **Policy consistency.** `OrderPolicy.php:13,26` read the guest token differently in `view()` vs `pay()`; unify.
- [ ] **Model symmetry.** Add `User::orders()`, `Address::orders()`; add unique constraint to `blog_tag_blog` pivot.
- [ ] **Low-stock/out-of-stock UX** (roadmap item): prevent adding out-of-stock items and show alerts.

---

## Suggested execution order

1. **Phase 0** — rotate secrets (no code risk).
2. **Phase 1.1 → 1.5** — order/payment integrity + proxy trust (highest risk).
3. **Phase 2** — auth (verification), soft deletes, casts, slug collisions, persistent middleware.
4. **Phase 3** — cache/config correctness, pagination, cart, reviews.
5. **Phase 4** — lint + polish.

After each phase:

```bash
vendor/bin/pint --test
php artisan test
npm run build
```

## Regression tests to add

- [ ] Stationery line item: order → invoice → stock decrement/restore.
- [ ] SSLCommerz: missing/mismatched `amount`, `val_id` amount mismatch, forged cancel/fail.
- [ ] Concurrent IPN + redirect → single transition, single restore.
- [ ] Duplicate slug for blog/event/category/author/tag/brand returns validation error, not 500.
- [ ] `verified` middleware actually blocks unverified users (or is intentionally removed).
- [ ] Persistent admin middleware blocks a non-admin Livewire update request.
