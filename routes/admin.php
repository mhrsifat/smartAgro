<?php

use App\Http\Controllers\AdminViewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HabizabiController;
use App\Http\Controllers\AdminSettingsController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin dashboard (admin home)
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/404', [AdminViewController::class, 'error404'])->name('error.404');

        // Contacts management
        Route::get('/contacts', [HabizabiController::class, 'admin_contacts'])->name('contacts.index');
        Route::get('/contacts/{contact}', [HabizabiController::class, 'showContact'])->name('contacts.show');
        Route::post('/contacts/{contact}/replied', [HabizabiController::class, 'markAsReplied'])->name('contacts.replied');
        Route::delete('/contacts/{contact}', [HabizabiController::class, 'destroyContact'])->name('contacts.destroy');
        
        // Show reply form
      Route::get('/contacts/{contact}/reply', [HabizabiController::class, 'replyForm'])->name('contacts.reply.form');

// Send reply
      Route::post('/contacts/{contact}/reply', [HabizabiController::class, 'sendReply'])->name('contacts.reply.send');
      
      
      Route::get('/settings', [\App\Http\Controllers\AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\AdminSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/header', [\App\Http\Controllers\AdminSettingsController::class, 'updateHeader'])->name('settings.header');
    Route::post('/settings/footer', [\App\Http\Controllers\AdminSettingsController::class, 'updateFooter'])->name('settings.footer');
    Route::post('/settings/hero', [\App\Http\Controllers\AdminSettingsController::class, 'updateHero'])->name('settings.hero');
    
    });
    
    