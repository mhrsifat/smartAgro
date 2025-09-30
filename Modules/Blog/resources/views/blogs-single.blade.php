@extends('layouts.master')

@section('title', $blog->title . ' - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('blogs.index') }}" 
       class="text-green-700 hover:underline mb-4 inline-flex items-center gap-1">
        <x-heroicon-o-arrow-left class="w-5 h-5"/> Back to Blogs
    </a>

    <h1 class="text-3xl font-bold text-green-700 mb-4 flex items-center gap-2">
        <x-heroicon-o-document-text class="w-8 h-8 text-green-600"/> {{ $blog->title }}
    </h1>

    @if($blog->image)
        @if(Str::startsWith($blog->image, ['http://', 'https://']))
            <img src="{{ $blog->image }}" 
                 alt="{{ $blog->title }}" 
                 class="w-full max-h-96 object-cover rounded mb-6">
        @else
            <img src="{{ asset('storage/' . $blog->image) }}" 
                 alt="{{ $blog->title }}" 
                 class="w-full max-h-96 object-cover rounded mb-6">
        @endif
    @endif

    <div class="prose max-w-none text-gray-700 mb-4">
        {!! nl2br(e($blog->content)) !!}
    </div>

    @if($blog->author)
        <x-bladewind::alert type="info" show_close_icon="false" class="mt-4">
            <x-heroicon-o-user class="w-5 h-5 inline-block mr-1"/>
            Written by <span class="font-medium">{{ $blog->author->name }}</span>
        </x-bladewind::alert>
    @endif

    <p class="text-gray-500 text-sm mt-2">
        <x-heroicon-o-calendar class="w-4 h-4 inline-block mr-1"/>
        Published: {{ $blog->created_at->format('M d, Y') }}
    </p>
</div>
@endsection