<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadController;
use App\Livewire\Admin\Books;
use App\Livewire\Admin\Authors;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Blogs;
use App\Livewire\Admin\BlogForm;
use App\Livewire\Admin\BlogCategories;
use App\Livewire\Admin\BlogTags;
use App\Livewire\Admin\Events;
use App\Livewire\Admin\EventForm;
use App\Livewire\Admin\Manuscripts;
use App\Livewire\Admin\MediaManager;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\OrderDetail;
use App\Livewire\Admin\Settings;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/books', Books::class)->name('books');
    Route::get('/authors', Authors::class)->name('authors');
    Route::get('/categories', Categories::class)->name('categories');

    Route::get('/blogs', Blogs::class)->name('blogs');
    Route::get('/blogs/create', BlogForm::class)->name('blogs.create');
    Route::get('/blogs/{blog}/edit', BlogForm::class)->name('blogs.edit');

    Route::get('/blog-categories', BlogCategories::class)->name('blog-categories');
    Route::get('/blog-tags', BlogTags::class)->name('blog-tags');
    Route::get('/manuscripts', Manuscripts::class)->name('manuscripts');

    Route::post('/upload-image', [UploadController::class, 'image'])->name('upload.image');

    Route::get('/orders', Orders::class)->name('orders');
    Route::get('/orders/{order}', OrderDetail::class)->name('orders.show');
    Route::get('/users', \App\Livewire\Admin\AdminUsers::class)->name('users');
    Route::get('/reviews', \App\Livewire\Admin\Reviews::class)->name('reviews');
    Route::get('/coupons', \App\Livewire\Admin\Coupons::class)->name('coupons');
    Route::get('/events', Events::class)->name('events');
    Route::get('/events/create', EventForm::class)->name('events.create');
    Route::get('/events/{event}/edit', EventForm::class)->name('events.edit');
    Route::get('/media', MediaManager::class)->name('media');
    Route::get('/newsletter', fn () => view('admin.placeholder', ['section' => 'Newsletter']))->name('newsletter');
    Route::get('/seo', fn () => view('admin.placeholder', ['section' => 'SEO']))->name('seo');
    Route::get('/reports', fn () => view('admin.placeholder', ['section' => 'Reports']))->name('reports');
    Route::get('/settings', Settings::class)->name('settings');
});
