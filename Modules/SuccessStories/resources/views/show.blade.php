@extends('layouts.master')

@section('title', $successstory->title . ' - Success Story')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        @if($successstory->image)
            @if (Str::startsWith($successstory->image, 'http'))
                <img src="{{ $successstory->image }}" alt="{{ $successstory->title }}"
                 class="w-full h-64 object-cover rounded mb-6">
            @else
                <img src="{{ asset('storage/' . $successstory->image) }}" alt="{{ $successstory->title }}"
                 class="w-full h-64 object-cover rounded mb-6">
            @endif
        @endif

        <h1 class="text-3xl font-bold text-green-700 mb-4">{{ $successstory->title }}</h1>
        @if($successstory->author)
            <p class="text-sm text-gray-500 mb-4">By {{ $successstory->author }}</p>
        @endif

        @if($successstory->summary)
            <p class="text-lg text-gray-700 mb-6">{{ $successstory->summary }}</p>
        @endif

        <div class="prose max-w-none">
            {!! nl2br(e($successstory->content)) !!}
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('success-stories.index') }}" 
           class="text-green-700 hover:underline">← Back to all stories</a>
    </div>
</div>
@endsection