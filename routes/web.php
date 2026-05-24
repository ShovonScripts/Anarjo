<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\AuthorController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\SslCommerzController;
use App\Http\Controllers\Frontend\BkashController;
use App\Http\Controllers\Frontend\OrderController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', fn() => response(view('seo.robots'))->header('Content-Type', 'text/plain'));

Route::get('/', [HomeController::class, 'index'])->name('home');

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

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
});

Route::get('/publication/submit', \App\Livewire\ManuscriptForm::class)->name('publication.submit');
Route::view('/publication', 'frontend.publication')->name('publication');

Route::get('/cart', \App\Livewire\Cart::class)->name('cart');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', \App\Livewire\Checkout::class)->name('checkout');
    Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::get('/order/{order}/payment', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');

    Route::post('/payment/sslcommerz/initiate/{order}', [SslCommerzController::class, 'initiate'])->name('payment.sslcommerz.initiate');

    Route::post('/payment/bkash/initiate/{order}', [BkashController::class, 'initiate'])->name('payment.bkash.initiate');
    Route::get('/payment/bkash/callback', [BkashController::class, 'callback'])->name('payment.bkash.callback');
});

Route::post('/payment/sslcommerz/success', [SslCommerzController::class, 'success'])->name('payment.sslcommerz.success');
Route::post('/payment/sslcommerz/cancel', [SslCommerzController::class, 'cancel'])->name('payment.sslcommerz.cancel');
Route::post('/payment/sslcommerz/fail', [SslCommerzController::class, 'fail'])->name('payment.sslcommerz.fail');
Route::post('/payment/sslcommerz/ipn', [SslCommerzController::class, 'ipn'])->name('payment.sslcommerz.ipn');

Route::view('about', 'frontend.about')->name('about');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
