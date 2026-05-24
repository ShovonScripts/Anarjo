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
use App\Livewire\Admin\Manuscripts;
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

    Route::get('/orders', fn () => view('admin.placeholder', ['section' => 'Orders']))->name('orders');
    Route::get('/users', fn () => view('admin.placeholder', ['section' => 'Users']))->name('users');
    Route::get('/reviews', fn () => view('admin.placeholder', ['section' => 'Reviews']))->name('reviews');
    Route::get('/coupons', fn () => view('admin.placeholder', ['section' => 'Coupons']))->name('coupons');
    Route::get('/events', fn () => view('admin.placeholder', ['section' => 'Events']))->name('events');
    Route::get('/media', fn () => view('admin.placeholder', ['section' => 'Media']))->name('media');
    Route::get('/newsletter', fn () => view('admin.placeholder', ['section' => 'Newsletter']))->name('newsletter');
    Route::get('/seo', fn () => view('admin.placeholder', ['section' => 'SEO']))->name('seo');
    Route::get('/reports', fn () => view('admin.placeholder', ['section' => 'Reports']))->name('reports');
    Route::get('/settings', Settings::class)->name('settings');
});
