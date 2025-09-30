<?php

use Illuminate\Support\Facades\Route;
use Modules\SuccessStories\Http\Controllers\SuccessStoriesController;
use Modules\SuccessStories\Http\Controllers\Admin\SuccessStoryController;

// Route::model('successstory', SuccessStory::class);

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
    Route::resource('successstories', SuccessStoryController::class)
        ->parameters(['successstories' => 'successStory']);
});

// Public routes
Route::prefix('success-stories')->group(function () {
    Route::get('/', [SuccessStoriesController::class, 'index'])
        ->name('success-stories.index');

    Route::get('{successstory:slug}', [SuccessStoriesController::class, 'show'])
        ->name('success-stories.show');
});
