@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Reset password</h2>

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/reset-password">
        @csrf

       <input type="hidden" name="token" value="{{ $request->route('token') }}">


        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" required value="{{ old('email') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">New Password</label>
            <input name="password" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input name="password_confirmation" type="password" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Reset Password</button>
    </form>
</div>
@endsection