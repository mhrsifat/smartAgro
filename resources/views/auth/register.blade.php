@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Create account</h2>

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Name</label>
            <input name="name" type="text" value="{{ old('name') }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input name="password" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input name="password_confirmation" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Register
        </button>
    </form>
    
<div class="mt-6">
  <p class="text-center text-gray-500">Or sign up with</p>
  <div class="flex justify-center gap-3 mt-3">
    <a href="{{ route('social.login', 'google') }}"
       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Google</a>
    <a href="{{ route('social.login', 'github') }}"
       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">GitHub</a>
  </div>
</div>

    <p class="mt-4 text-sm">
        Already have an account?
        <a href="/login" class="text-blue-600 hover:underline">Login</a>
    </p>
</div>
@endsection