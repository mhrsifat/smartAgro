@extends('layouts.master')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>

    @if(session('status'))
        <div class="mb-4 text-green-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Name</label>
            <input name="name" type="text" value="{{ old('name', $user->name) }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" required
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save changes</button>
    </form>
</div>
@endsection