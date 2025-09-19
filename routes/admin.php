<?php

use App\Http\Controllers\AdminViewController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/', [AdminViewController::class, 'dashboard'])->name('dashboard');
    Route::get('/buttons', [AdminViewController::class, 'buttons'])->name('buttons');
    Route::get('/cards', [AdminViewController::class, 'cards'])->name('cards');
    Route::get('/charts', [AdminViewController::class, 'charts'])->name('charts');
    Route::get('/forms', [AdminViewController::class, 'forms'])->name('forms');
    Route::get('/modals', [AdminViewController::class, 'modals'])->name('modals');
    Route::get('/tables', [AdminViewController::class, 'tables'])->name('tables');

    Route::get('/404', [AdminViewController::class, 'error404'])->name('error.404');
    Route::get('/blank', [AdminViewController::class, 'blank'])->name('blank');
    Route::get('/create-account', [AdminViewController::class, 'createAccount'])->name('create.account');
    Route::get('/forgot-password', [AdminViewController::class, 'forgotPassword'])->name('forgot.password');
    Route::get('/login', [AdminViewController::class, 'login'])->name('login');
});