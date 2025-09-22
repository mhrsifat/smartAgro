<?php

use Illuminate\Support\Facades\Route;
use Modules\Research\Http\Controllers\ResearchController;
use Modules\Research\Http\Controllers\AdminResearchController;


// Public/Authenticated Users (view only)
Route::prefix('research')->group(function () {
    Route::get('/', [ResearchController::class, 'index'])->name('research.index');
    Route::get('{research}', [ResearchController::class, 'show'])->name('research.show');
});



Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'web', 'admin'])->group(function () {
    Route::resource('researches', AdminResearchController::class);
});
