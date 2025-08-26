<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CropController;
use App\Http\Controllers\DiseaseController;

/* Route::get('/', function () {
    return view('welcome');
}); **/

//Route::get('/login', []);


Route::get('/', [CropController::class, 'getSuggestion']);
Route::get('/disease', [DiseaseController::class, 'diseasePage']);
Route::post('/disease', [DiseaseController::class, 'analyze'])->name('disease.analyze');
Route::get('/diagnosis-status', [DiseaseController::class, 'checkDiagnosis']);
