@extends('layouts.master')

@section('title', 'Confirm Password')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded-2xl p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Password</h2>
    <p class="text-sm text-gray-600 mb-6">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                Confirm
            </button>
        </div>
    </form>
</div>
@endsection