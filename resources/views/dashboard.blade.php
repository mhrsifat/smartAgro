@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Card -->
    <div class="bg-white shadow rounded-2xl p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Profile Photo -->
            <div class="relative w-32 h-32">
    <form method="POST" action="{{ route('user.profile-photo.update') }}" enctype="multipart/form-data" class="w-full h-full">
        @csrf
        @method('PATCH')

        <!-- Hidden file input -->
        <input type="file" name="photo" class="hidden" id="profile-photo-input" onchange="this.form.submit()">

        <!-- Clickable image + overlay -->
        <label for="profile-photo-input" class="cursor-pointer relative block w-full h-full">
            <img 
                src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                alt="Profile Photo" 
                class="w-full h-full rounded-full object-cover border shadow-sm"
            />

            <!-- Overlay -->
            <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-8 w-8 text-white" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h2l1-2h12l1 2h2v14H3V7z" />
                    <circle cx="12" cy="13" r="3" />
                </svg>
            </div>
        </label>
    </form>
</div>

            <!-- User Info -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                <p class="mt-2 text-sm text-gray-500">Joined {{ Auth::user()->created_at->format('M d, Y') }}</p>
                @if(! Auth::user()->hasVerifiedEmail())
                    <div class="mt-2 text-sm text-yellow-600">Email not verified — <a href="{{ url('/email/verify') }}" class="underline">verify now</a></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Account Management -->
    <div class="grid gap-6 md:grid-cols-2">
     
     @role('admin')
    <a href="{{ route('admin.dashboard') }}" 
       class="bg-white shadow hover:shadow-md transition rounded-2xl p-6 flex flex-col items-start">
        <h3 class="text-lg font-semibold text-gray-900">Admin Dashboard</h3>
        <p class="text-gray-600 text-sm mt-2">Go to Admin dashboard.</p>
    </a>
@endrole
      
        <a href="{{ route('user-password.edit') }}" class="bg-white shadow hover:shadow-md transition rounded-2xl p-6 flex flex-col items-start">
            <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
            <p class="text-gray-600 text-sm mt-2">Update your account password to keep your account secure.</p>
        </a>

        <a href="{{ route('profile.show') }}" class="bg-white shadow hover:shadow-md transition rounded-2xl p-6 flex flex-col items-start">
            <h3 class="text-lg font-semibold text-gray-900">Update Profile & Email</h3>
            <p class="text-gray-600 text-sm mt-2">Change your email address or update your personal details.</p>
        </a>

        <a href="{{ route('two-factor.show') }}" class="bg-white shadow hover:shadow-md transition rounded-2xl p-6 flex flex-col items-start">
            <h3 class="text-lg font-semibold text-gray-900">Two-Factor Authentication</h3>
            <p class="text-gray-600 text-sm mt-2">Add an extra layer of security to your account with 2FA.</p>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="bg-white shadow hover:shadow-md transition rounded-2xl p-6 flex flex-col items-start">
            @csrf
            <button type="submit" class="w-full text-left">
                <h3 class="text-lg font-semibold text-gray-900">Logout</h3>
                <p class="text-gray-600 text-sm mt-2">Sign out of your account safely.</p>
            </button>
        </form>
    </div>
</div>
@endsection