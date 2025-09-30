@extends('layouts.master')

@section('title', $research->title . ' - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('research.index') }}" class="text-green-700 hover:underline mb-4 inline-block">← Back to Researches</a>

    <h1 class="text-3xl font-bold text-green-700 mb-4">{{ $research->title }}</h1>

    @if($research->image)
        {{-- If starts with http or https --}}
        @if(Str::startsWith($research->image, ['http://', 'https://']))
            <img src="{{ $research->image }}" alt="{{ $research->title }}" class="w-full max-h-96 object-cover rounded mb-6">
        @else
            <img src="{{ asset($research->image) }}" alt="{{ $research->title }}" class="w-full max-h-96 object-cover rounded mb-6">
        @endif
    @endif

    <p class="text-gray-700 mb-4">{!! nl2br(e($research->description)) !!}</p>

    @if($research->authors)
        <p class="text-gray-600 font-medium">Authors: {{ $research->authors }}</p>
    @endif

    @if($research->download_url)
        <a href="{{ asset($research->download_url) }}" target="_blank" class="inline-block mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download Research Paper</a>
    @endif
</div>
@endsection
