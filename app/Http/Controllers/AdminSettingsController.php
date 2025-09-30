<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateHeaderRequest;
use App\Http\Requests\UpdateFooterRequest;
use App\Http\Requests\UpdateHeroRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'header' => Setting::get('header', []),
            'footer' => Setting::get('footer', []),
            'hero' => Setting::get('hero', []),
        ];

        return view('admin.settings.index', [
            'settings' => $settings,
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->name = $request->input('name');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->storePublicly('profile_photos', ['disk' => 'public']);
            // delete old file if exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function updateHeader(UpdateHeaderRequest $request)
    {
        $header = Setting::get('header', []);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->storePublicly('site/logo', ['disk' => 'public']);
            // delete old logo if exists
            if (!empty($header['logo']) && Storage::disk('public')->exists($header['logo'])) {
                Storage::disk('public')->delete($header['logo']);
            }
            $header['logo'] = $path;
        }

        if ($request->filled('nav_links')) {
            $header['nav_links'] = $request->input('nav_links'); // as array
        }

        Setting::set('header', $header);

        return back()->with('success', 'Header settings saved.');
    }

    public function updateFooter(UpdateFooterRequest $request)
    {
        $footer = Setting::get('footer', []);

        if ($request->filled('footer_text')) {
            $footer['footer_text'] = $request->input('footer_text');
        }

        if ($request->filled('social_links')) {
            $footer['social_links'] = $request->input('social_links');
        }

        Setting::set('footer', $footer);

        return back()->with('success', 'Footer settings saved.');
    }

    public function updateHero(UpdateHeroRequest $request)
    {
        $hero = Setting::get('hero', []);

        if ($request->filled('title')) $hero['title'] = $request->input('title');
        if ($request->filled('subtitle')) $hero['subtitle'] = $request->input('subtitle');
        if ($request->filled('cta')) $hero['cta'] = $request->input('cta');

        if ($request->hasFile('background')) {
            $file = $request->file('background');
            $path = $file->storePublicly('site/hero', ['disk' => 'public']);
            if (!empty($hero['background']) && Storage::disk('public')->exists($hero['background'])) {
                Storage::disk('public')->delete($hero['background']);
            }
            $hero['background'] = $path;
        }

        Setting::set('hero', $hero);

        return back()->with('success', 'Hero settings saved.');
    }
}