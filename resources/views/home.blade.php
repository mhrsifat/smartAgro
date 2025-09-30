@extends('layouts.master')

@section('title', 'Home - SmartAgro')
@section('content')
<div class="container mx-auto px-4 py-6 space-y-12">

  {{-- Call to Action Section --}}
  <section class="bg-gray-50 py-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6 text-center">Quick Tools</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="{{ route('pesticide.suggestor') }}" class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Pesticide Suggestor
      </a>
      <a href="{{ route('crop.planner') }}" class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Plant Suggestor (Crop Planner)
      </a>
      <a href="{{ route('fertilizer.suggestor') }}" class="flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Fertilizer Suggestor
      </a>
      <a href="{{ route('disease.scan') }}" class="flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Disease Scan
      </a>
    </div>
  </section>

  {{-- Research Section --}}
  <section>
    <h2 class="text-2xl font-semibold mb-4">Latest Research</h2>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($researches as $research)
        <a href="{{ route('research.show', $research->slug) }}" class="block bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
          @if (Str::startsWith($research->image, ['http://', 'https://']))
              <img src="{{ $research->image }}" 
                   alt="{{ $research->title }}" 
                   class="w-full h-40 object-cover rounded mb-3">
          @else
              <img src="{{ asset('storage/' . $research->image) }}"
                   alt="{{ $research->title }}"
                   class="w-full h-40 object-cover rounded mb-3">
{{-- Call to Action Section --}}
  <section class="bg-gray-50 py-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6 text-center">Quick Tools</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="{{ route('pesticide.suggestor') }}" class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Pesticide Suggestor
      </a>
      <a href="{{ route('crop.planner') }}" class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Plant Suggestor (Crop Planner)
      </a>
      <a href="{{ route('fertilizer.suggestor') }}" class="flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Fertilizer Suggestor
      </a>
      <a href="{{ route('disease.scan') }}" class="flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded-lg transition-colors">
        Disease Scan
      </a>
    </div>
  </section>          @endif
          <div class="p-4">
            <h3 class="text-lg font-bold mb-2 text-gray-800">{{ $research->title }}</h3>
            <p class="text-sm text-gray-600">{{ Str::limit($research->excerpt ?? $research->description, 100) }}</p>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  {{-- Blog Section --}}
  <section>
    <h2 class="text-2xl font-semibold mb-4">From Our Blog</h2>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($blogs as $blog)
        <a href="{{ route('blogs.show', $blog->slug) }}" class="block bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
          @if (Str::startsWith($blog->image, ['http://', 'https://']))
              <img src="{{ $blog->image }}" 
                   alt="{{ $blog->title }}" 
                   class="w-full h-40 object-cover rounded mb-3">
          @else
              <img src="{{ asset('storage/' . $blog->image) }}"
                   alt="{{ $blog->title }}"
                   class="w-full h-40 object-cover rounded mb-3">
          @endif
          <div class="p-4">
            <h3 class="text-lg font-bold mb-2 text-gray-800">{{ $blog->title }}</h3>
            <p class="text-sm text-gray-600">{{ Str::limit($blog->excerpt ?? $blog->content, 100) }}</p>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  {{-- Success Stories Section --}}
  <section>
    <h2 class="text-2xl font-semibold mb-4">Success Stories</h2>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($successStories as $story)
        <a href="{{ route('success-stories.show', $story->slug) }}" class="block bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
          @if (Str::startsWith($story->image, ['http://', 'https://']))
              <img src="{{ $story->image }}" 
                   alt="{{ $story->title }}" 
                   class="w-full h-40 object-cover rounded mb-3">
          @else
              <img src="{{ asset('storage/' . $story->image) }}"
                   alt="{{ $story->title }}"
                   class="w-full h-40 object-cover rounded mb-3">
          @endif
          <div class="p-4">
            <h3 class="text-lg font-bold mb-2 text-gray-800">{{ $story->title }}</h3>
            <p class="text-sm text-gray-600">{{ Str::limit($story->excerpt ?? $story->content, 100) }}</p>
          </div>
        </a>
      @endforeach
    </div>
  </section>

</div>
@endsection
