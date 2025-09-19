<?php

use Illuminate\Support\Facades\Route;
use Modules\Research\Http\Controllers\ResearchController;
use Modules\Research\Http\Controllers\AdminResearchController;


// Public/Authenticated Users (view only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('researches', [ResearchController::class, 'index'])->name('research.index');
    Route::get('researches/{research}', [ResearchController::class, 'show'])->name('research.show');
});

// Admin (full CRUD)
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::resource('researches', AdminResearchController::class, [
        'as' => 'admin'
    ]);
});
