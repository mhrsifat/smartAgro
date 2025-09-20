<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CropController;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/recommend-crop', [CropController::class, 'recommendCrop']);



Route::get('/notifications/unread', function () {
    $user = Auth::user();
    if (!$user) {
        return response()->json([]);
    }

    return $user->unreadNotifications()
        ->take(10)
        ->get()
        ->map(function ($note) {
            return [
                'id' => $note->id,
                'message' => strip_tags($note->data['message']),
                'url' => $note->data['url'] ?? null,
            ];
        });
})->middleware('auth');