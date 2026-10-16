<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\AuthorController;
use App\Http\Controllers\Frontend\BkashController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\BookFairController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\SslCommerzController;
use App\Http\Controllers\Frontend\StationeryController;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\ManuscriptForm;
use App\Livewire\WishlistPage;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', fn () => response(view('seo.robots'))->header('Content-Type', 'text/plain'));

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:3,10')
    ->name('newsletter.subscribe');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{slug}', [BookController::class, 'show'])->name('show');
});

Route::prefix('category')->name('category.')->group(function () {
    Route::get('/{slug}', [BookController::class, 'category'])->name('books');
});

Route::prefix('author')->name('author.')->group(function () {
    Route::get('/{slug}', [AuthorController::class, 'show'])->name('show');
});

Route::prefix('stationeries')->name('stationeries.')->group(function () {
    Route::get('/', [StationeryController::class, 'index'])->name('index');
    Route::get('/brand/{slug}', [StationeryController::class, 'brand'])->name('brand');
    Route::get('/{slug}', [StationeryController::class, 'show'])->name('show');
});

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
});

Route::prefix('book-fair')->name('book-fair.')->group(function () {
    Route::get('/', [BookFairController::class, 'index'])->name('index');
    Route::get('/{slug}', [BookFairController::class, 'show'])->name('show');
});

Route::get('/publication/submit', ManuscriptForm::class)->name('publication.submit');
Route::view('/publication', 'frontend.publication')->name('publication');

Route::get('/cart', Cart::class)->name('cart');

Route::middleware('auth')->get('/wishlist', WishlistPage::class)->name('wishlist');

Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Invoice routes are public — guest orders are protected via confirmation_token query param
Route::get('/order/{order}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
Route::get('/order/{order}/invoice/preview', [OrderController::class, 'invoicePreview'])->name('order.invoice.preview');

// Payment processing & initiation (protected via OrderPolicy with guest token support)
Route::get('/order/{order}/payment', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/payment/sslcommerz/initiate/{order}', [SslCommerzController::class, 'initiate'])->name('payment.sslcommerz.initiate');
Route::post('/payment/bkash/initiate/{order}', [BkashController::class, 'initiate'])->name('payment.bkash.initiate');
Route::match(['GET', 'POST'], '/payment/bkash/callback', [BkashController::class, 'callback'])->name('payment.bkash.callback');

// SSLCommerz callbacks (SSLCommerz submits via HTTP POST on browser redirect & IPN)
Route::match(['GET', 'POST'], '/payment/sslcommerz/success', [SslCommerzController::class, 'success'])->name('payment.sslcommerz.success');
Route::match(['GET', 'POST'], '/payment/sslcommerz/cancel', [SslCommerzController::class, 'cancel'])->name('payment.sslcommerz.cancel');
Route::match(['GET', 'POST'], '/payment/sslcommerz/fail', [SslCommerzController::class, 'fail'])->name('payment.sslcommerz.fail');
Route::match(['GET', 'POST'], '/payment/sslcommerz/ipn', [SslCommerzController::class, 'ipn'])->name('payment.sslcommerz.ipn');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::view('privacy-policy', 'frontend.policies.privacy')->name('privacy-policy');
Route::view('terms-conditions', 'frontend.policies.terms')->name('terms-conditions');
Route::view('return-refund', 'frontend.policies.return-refund')->name('return-refund');

Route::get('about', [AboutController::class, 'index'])->name('about');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
