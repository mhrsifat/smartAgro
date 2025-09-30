<?php

use Illuminate\Support\Facades\Route;
use Modules\SuccessStories\Http\Controllers\SuccessStoriesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('successstories', SuccessStoriesController::class)->names('successstories');
});
