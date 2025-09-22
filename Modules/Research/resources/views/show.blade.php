@extends('layouts.master')

@section('title', $research->title . ' - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('research.index') }}" class="text-green-700 hover:underline mb-4 inline-block">← Back to Researches</a>

    <h1 class="text-3xl font-bold text-green-700 mb-4">{{ $research->title }}</h1>

    @if($research->image)
        <img src="{{ asset('storage/' . $research->image) }}" alt="{{ $research->title }}" class="w-full max-h-96 object-cover rounded mb-6">
    @endif

    <p class="text-gray-700 mb-4">{!! nl2br(e($research->description)) !!}</p>

    @if($research->authors)
        <p class="text-gray-600 font-medium">Authors: {{ $research->authors }}</p>
    @endif
</div>
@endsection