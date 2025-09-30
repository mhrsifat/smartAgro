@extends('layouts.master')

@section('title', 'Verify your email')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Verify your email address</h2>

    <p class="mb-4 text-sm text-gray-700">
        A verification link has been sent to your email address. Please check your inbox and click the link to verify your account.
    </p>

    @if(session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm text-green-700">A fresh verification link has been sent to your email address.</div>
    @endif

    <form method="POST" action="/email/verification-notification">
        @csrf
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Resend Verification Email</button>
    </form>

    <form method="POST" action="/logout" class="mt-3">
        @csrf
        <button type="submit" class="text-sm text-red-600 hover:underline">Log out</button>
    </form>
</div>
@endsection