@extends('layouts.master')

@section('title', 'Thank You')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow-md rounded text-center">
    <h1 class="text-2xl font-bold mb-4">Thank You!</h1>
    <p class="mb-4">Your donation has been received successfully.</p>
    <a href="{{ route('donation.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Make Another Donation</a>
</div>
@endsection