@extends('layouts.master')

@section('title', 'Change Password')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Change Password</h2>

    @if(session('status'))
        <div class="mb-4 text-green-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('user-password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Current password</label>
            <input name="current_password" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            @error('current_password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">New password</label>
            <input name="password" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Confirm new password</label>
            <input name="password_confirmation" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update password</button>
    </form>
</div>
@endsection