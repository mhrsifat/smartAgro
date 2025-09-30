@extends('layouts.master')

@section('title', 'Two-Factor Challenge')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Two-Factor Authentication</h2>

    <p class="text-sm mb-4">Enter the authentication code from your authenticator app, or use one of your recovery codes.</p>

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/two-factor-challenge">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Authentication code</label>
            <input name="code" type="text" autofocus
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Recovery code</label>
            <input name="recovery_code" type="text"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            <p class="text-xs text-gray-500 mt-2">Use either an authentication code or a recovery code (one-time use).</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Verify</button>
    </form>
</div>
@endsection