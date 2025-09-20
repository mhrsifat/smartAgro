<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;
use Modules\Blog\Http\Controllers\AdminBlogController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('blogs', AdminBlogController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('blog/{blog}', [BlogController::class, 'show'])->name('blog.show');
});


