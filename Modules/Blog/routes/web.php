<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;
use Modules\Blog\Http\Controllers\AdminBlogController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('blogs', AdminBlogController::class)->names('admin.blogs');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
});


