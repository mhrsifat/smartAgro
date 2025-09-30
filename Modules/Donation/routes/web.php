<?php

use Illuminate\Support\Facades\Route;
use Modules\Donation\Http\Controllers\DonationController;
use Modules\Donation\Http\Controllers\Admin\DonationController as AdminDonationController;



Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'web', 'admin'])->group(function () {
    
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/create', [AdminDonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [AdminDonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}/show', [AdminDonationController::class, 'show'])->name('donations.show');
    Route::patch('/donations/{donation}/status', [AdminDonationController::class, 'updateStatus'])->name('donations.updateStatus');
    Route::post('/donations/bulk-update', [AdminDonationController::class, 'bulkUpdate'])->name('donations.bulkUpdate');
    Route::get('/donations/export', [AdminDonationController::class, 'export'])->name('donations.export');
    Route::get('/donations/report', [AdminDonationController::class, 'report'])->name('donations.report');
    Route::get('/donations/report/pdf', [AdminDonationController::class, 'downloadReportPdf'])->name('donations.report.pdf');
});





Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('donation');
    Route::get('/create', [DonationController::class, 'create'])->name('create');
    Route::post('/store', [DonationController::class, 'store'])->name('store');
    Route::get('/thankyou', [DonationController::class, 'thankyou'])->name('thankyou');
    
    Route::post('/ssl/success', [DonationController::class, 'sslSuccess'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('ssl.success');
    Route::post('/ssl/fail', [DonationController::class, 'sslFail'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('ssl.fail');
    Route::post('/ssl/cancel', [DonationController::class, 'sslCancel'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('ssl.cancel');
        
    Route::post('/bkash/callback', [DonationController::class, 'bkashCallback'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('bkash.callback');
        
    Route::post('/nagad/callback', [DonationController::class, 'nagadCallback'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('nagad.callback');
});

