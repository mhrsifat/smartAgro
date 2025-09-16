<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function update_photo(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // only image files up to 2MB
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Save to user model (make sure 'profile_photo_path' exists in users table)
        $user->profile_photo_path = $path;
        $user->save();

        return back()->with('status', 'Profile photo updated successfully!');
    }
}
