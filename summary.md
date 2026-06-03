## Goal
- Build a complete book-publishing e-commerce platform (Anarjo Publications) with blogs, events, media library, orders, comments/likes, and professional admin panel.

## Constraints & Preferences
- Laravel 12, Livewire 3, Tailwind CSS, MySQL (XAMPP)
- Bengali (Bangla) content for a Bangladeshi audience
- Polymorphic architecture for comments and likes across books and blogs
- Admin sidebar uses collapsible dropdowns (Blog → Books pattern)
- All file uploads tracked in `media` database table
- Quill rich text editor for content fields

## Progress

### Done
- Polymorphic `comments` and `likes` system across Book + Blog models (migrations, models, Livewire components)
- Admin sidebar: Books dropdown with sub-items (All Books, Categories, Authors)
- Events system: full CRUD with `EventForm` (dedicated form page like BlogForm), slug/status, filters, Quill editor
- 15 demo events seeded (Bengali, 2018–2026 book fairs)
- Fixed event index showing raw HTML tags (`strip_tags()`)
- Fixed event edit 404 (`Event::where('slug', ...)->firstOrFail()` instead of `findOrFail`)
- Media Library (`/admin/media`): upload, grid view, Copy URL, Delete, search/filter, pagination
- All 6 file upload points now create `Media` records (EventForm, BlogForm, Settings, Books, ManuscriptForm, UploadController)
- Admin Orders listing + OrderDetail page with status update
- Author photo upload in admin Authors form
- Category image upload in admin Categories form
- Frontend wishlist page (`/wishlist`) with bookmark toggle on book detail page
- User order history (`/orders`) paginated list with delivery status
- Admin Coupons CRUD (fixed/percent, min amount, date range, usage limits)
- Coupon code integration in checkout flow (apply/validate/discount)
- Book Reviews: frontend star rating + text submission (pending approval), approved reviews displayed
- Admin Reviews moderation: approve/reject/delete with status filter and search
- Dashboard real stats: revenue, orders, books, reviews, authors, users, blogs, categories
- Book form: preview PDF upload, publisher select, SEO title/description fields added
- Admin Users management: edit name/email/phone/status, search
- All 6 placeholder admin routes replaced with real components

### In Progress
- (none)

### Blocked
- (none)

## Key Decisions
- `Event` uses `slug` as route key name — required changing admin `mount()` to query by slug, not ID
- File uploads across all features write to `media` table so `/admin/media` shows every uploaded file
- Admin order detail is a single-page Livewire component with status form, not a separate controller
- Author photo uses `WithFileUploads` + direct `Media::create()`, consistent with other upload patterns
- Coupons model uses `isValid()` method encapsulating date/status/usage logic
- Reviews use `pending → approved/rejected` workflow, not a simple toggle

## Next Steps
- Stock management: prevent out-of-stock purchases, low stock alerts
- Newsletter subscription + email campaigns
- Reports page with sales/monthly/period charts
- SEO meta management per page
- Coupon auto-expire cron job
- Email notifications for order status changes
- PDF invoice generation
- Frontend author page with book listing
- Payment webhook handling for SSLCommerz/bKash

## Critical Context
- The `Event` model overrides `getRouteKeyName()` to `slug` — any `findOrFail()` on a slug string will 404; must use `where('slug', $value)->firstOrFail()`
- `Str::limit()` on Quill HTML content without `strip_tags()` shows raw HTML tags on frontend cards
- 6 placeholder admin routes all replaced (orders, users, reviews, coupons were real; newsletter/seo/reports still need building)

## Relevant Files
- `routes/admin.php`: all admin route definitions
- `routes/web.php`: frontend routes including auth group for orders/wishlist/checkout
- `app/Models/Coupon.php`: has `isValid()` method, `min_amount` and `usage_count` migration added
- `app/Models/Review.php`: status workflow (pending/approved/rejected)
- `app/Livewire/Admin/Coupons.php`: full CRUD for coupons
- `app/Livewire/Admin/Reviews.php`: approve/reject/delete with filters
- `app/Livewire/Admin/AdminUsers.php`: edit user details/status
- `app/Livewire/Admin/Books.php`: now includes preview_pdf, publisher_id, seo fields
- `app/Livewire/Checkout.php`: applyCoupon/removeCoupon/getDiscount methods added
- `app/Livewire/BookReviews.php`: frontend review form + approved review listing
- `app/Livewire/BookWishlist.php`: toggle wishlist component
- `app/Livewire/WishlistPage.php`: user's saved books listing
- `app/Http/Controllers/Frontend/OrderController.php`: added `index()` method for order history
- `app/Http/Controllers/Admin/DashboardController.php`: now passes real `$stats` with 10 metrics
- `resources/views/livewire/checkout.blade.php`: coupon input + discount row added
- `resources/views/frontend/books/show.blade.php`: now renders BookReviews + BookWishlist components
- `resources/views/livewire/book-reviews.blade.php`: star rating + form + list
- `resources/views/frontend/orders/index.blade.php`: order history with items preview
- `resources/views/livewire/wishlist-page.blade.php`: wishlist grid with remove button
