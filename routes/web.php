<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CropController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HabizabiController;
use App\Http\Controllers\Fortify\ProfilePhotoController;
use App\Http\Controllers\Fortify\ProfileController;
use App\Http\Controllers\Fortify\PasswordController;
use App\Http\Controllers\Fortify\TwoFactorController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;

Route::get('/test', function () {
    return view('test');
});

// add to routes/web.php temporarily
Route::get('/whoami', function () {
    return \Illuminate\Support\Facades\Auth::user() ?: 'NO USER';
});

require __DIR__.'/admin.php';


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/contact', [HabizabiController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HabizabiController::class, 'submit'])->name('contact.submit');

Route::get('/suggestion', [CropController::class, 'getSuggestion']);
Route::get('/disease', [DiseaseController::class, 'diseasePage']);
Route::post('/disease', [DiseaseController::class, 'analyze'])->name('disease.analyze');
Route::get('/diagnosis-status', [DiseaseController::class, 'checkDiagnosis']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HabizabiController::class, 'dashboard'])->name('dashboard');

    // Profile photo
    Route::patch('/user/profile-photo', [ProfilePhotoController::class, 'update'])->name('user.profile-photo.update');
    Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])->name('user.profile-photo.destroy');

    // Profile edit (name / email)
    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('profile.show');
    Route::put('/user/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Password change pages
    Route::get('/user/password', [PasswordController::class, 'edit'])->name('user-password.edit');
    Route::put('/user/password', [PasswordController::class, 'update'])->name('user-password.update');

    // Two-Factor management UI (forms submit to Fortify endpoints)
    Route::get('/user/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
});
 
// Social login
Route::get('auth/{provider}', [SocialLoginController::class, 'redirect'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback');

Route::get('/user/email/verify-new', [ProfileController::class, 'verifyNewEmail'])
    ->name('user.email.verify-new');

Route::get('/user/email/cancel-change', [ProfileController::class, 'cancelEmailChange'])
    ->name('user.email.cancel');
    


Route::get('/diagnoses/{id}', [DiseaseController::class, 'show'])->name('diagnosis.result.show');
Route::get('/diagnosis/result/{id}', [DiseaseController::class, 'resultShow'])->name('diagnosis.result.show');




Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/all', [NotificationController::class, 'all'])->name('notifications.all');
});







// Route::get('/notifications/unread', function () {
//     $user = Auth::user(); // session user

//     if (!$user) {
//         return response()->json([]); // empty if not logged in
//     }

//     return $user->notifications()
//         ->whereNull('read_at')
//         ->take(10)
//         ->get()
//         ->map(function ($note) {
//             return [
//                 'id' => $note->id,
//                 'message' => strip_tags($note->data['message']),
//                 'url' => $note->data['url'] ?? null,
//             ];
//         });
// })->middleware('auth');