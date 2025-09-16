<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Download profile picture if available
        $profilePhotoPath = null;
        if ($socialUser->getAvatar()) {
            try {
                $photoContents = Http::get($socialUser->getAvatar())->body();
                $filename = 'profile-photos/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($filename, $photoContents);
                $profilePhotoPath = $filename;
            } catch (\Exception $e) {
                // ignore if avatar can't be fetched
            }
        }

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
                'profile_photo_path' => $profilePhotoPath,
            ]
        );

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}