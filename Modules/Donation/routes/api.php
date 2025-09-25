<?php

use Illuminate\Support\Facades\Route;
use Modules\Donation\Http\Controllers\DonationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('donations', DonationController::class)->names('donation');
});
