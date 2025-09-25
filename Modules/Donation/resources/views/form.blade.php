@extends('layouts.master')

@section('title', 'Make a Donation')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow-md rounded">
    <h1 class="text-2xl font-bold mb-4">Make a Donation</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 mb-4 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('donation.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="donor_name" value="{{ old('donor_name', $user->name ?? '') }}" class="w-full border rounded p-2">
            @error('donor_name') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="donor_email" value="{{ old('donor_email', $user->email ?? '') }}" class="w-full border rounded p-2">
            @error('donor_email') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Phone</label>
            <input type="text" name="donor_phone" value="{{ old('donor_phone') }}" class="w-full border rounded p-2">
            @error('donor_phone') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" value="{{ old('amount') }}" class="w-full border rounded p-2" required>
            @error('amount') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Payment Gateway</label>
            <select name="payment_gateway" class="w-full border rounded p-2" required>
                <option value="">Select</option>
                <option value="sslcommerz">SSLCommerz</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
            </select>
            @error('payment_gateway') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="anonymous" value="1" class="mr-2">
                Donate Anonymously
            </label>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Message (optional)</label>
            <textarea name="message" class="w-full border rounded p-2">{{ old('message') }}</textarea>
            @error('message') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Donate Now</button>
    </form>
</div>
@endsection