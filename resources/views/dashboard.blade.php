@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Card -->
    <div class="bg-white shadow rounded-2xl p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Profile Photo -->
            <div class="relative">
                <img 
                    src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                    alt="Profile Photo" 
                    class="w-32 h-32 rounded-full object-cover border shadow-sm"
                />

                <form method="POST" action="{{ route('user.profile-photo.update') }}" enctype="multipart/form-data" class="absolute bottom-0 right-0">
                    @csrf
                    @method('PATCH')
                    <label class="cursor-pointer bg-blue-600 text-white p-2 rounded-full shadow hover:bg-blue-700" title="Change photo">
                        <input type="file" name="photo" class="hidden" onchange="this.form.submit()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l4 4M3 21h18" />
                        </svg>
                    </label>
                </form>

                <form method="POST" action="{{ route('user.profile-photo.destroy') }}" class="absolute top-0 right-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gray-100 text-gray-700 p-1 rounded-full shadow hover:bg-gray-200" title="Remove photo">
                        &times;
                    </button>
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