<?php

use Illuminate\Support\Facades\Route;
use Modules\Research\Http\Controllers\ResearchController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('research', ResearchController::class)->names('research');
});
