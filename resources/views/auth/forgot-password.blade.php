@extends('layouts.master')

@section('title', 'Forgot Password')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Forgot password</h2>

    @if(session('status'))
        <div class="mb-4 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/forgot-password">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" required value="{{ old('email') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Send reset link</button>
    </form>

    <p class="mt-4 text-sm"><a href="/login" class="text-blue-600 hover:underline">Back to login</a></p>
</div>
@endsection