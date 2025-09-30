@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Login</h2>

    @if(session('status'))
        <div class="mb-4 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-2">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input name="password" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="flex items-center justify-between mb-4">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="remember" class="mr-2">
                Remember me
            </label>

            <a href="/forgot-password" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Log in
        </button>
    </form>
    
    <div class="mt-6">
  <p class="text-center text-gray-500">Or login with</p>
  <div class="flex justify-center gap-3 mt-3">
    <a href="{{ route('social.login', 'google') }}"
       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Google</a>
    <a href="{{ route('social.login', 'github') }}"
       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">GitHub</a>
  </div>
</div>

    <p class="mt-4 text-sm">
        Don't have an account?
        <a href="/register" class="text-blue-600 hover:underline">Register</a>
    </p>
</div>
@endsection