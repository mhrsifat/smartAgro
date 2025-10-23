@extends('layouts.master')

@section('title', 'Home - SmartAgro')
@section('content')
<div class="container mx-auto px-4 py-6 space-y-12">

  {{-- Call to Action Section --}}
  <section class="bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8 rounded-xl shadow-sm">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center text-gray-800">Quick Tools</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            
            <!-- Pesticide Suggestor Card -->
            <a href="{{ route('pesticide.suggestor') }}" class="group relative overflow-hidden bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6 flex flex-col items-center justify-center min-h-[160px] space-y-3">
                    <div class="w-14 h-14 rounded-full bg-green-100 group-hover:bg-white flex items-center justify-center transition-colors duration-300">
                        <svg class="w-7 h-7 text-green-600 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-white transition-colors duration-300 text-center">Pesticide Suggestor</h3>
                </div>
            </a>

            <!-- Plant Suggestor Card -->
            <a href="{{ route('crop.planner') }}" class="group relative overflow-hidden bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6 flex flex-col items-center justify-center min-h-[160px] space-y-3">
                    <div class="w-14 h-14 rounded-full bg-blue-100 group-hover:bg-white flex items-center justify-center transition-colors duration-300">
                        <svg class="w-7 h-7 text-blue-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-white transition-colors duration-300 text-center">Plant Suggestor</h3>
                    <p class="text-xs text-gray-500 group-hover:text-white/80 transition-colors duration-300">(Crop Planner)</p>
                </div>
            </a>

            <!-- Fertilizer Suggestor Card -->
            <a href="{{ route('fertilizer.suggestor') }}" class="group relative overflow-hidden bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6 flex flex-col items-center justify-center min-h-[160px] space-y-3">
                    <div class="w-14 h-14 rounded-full bg-orange-100 group-hover:bg-white flex items-center justify-center transition-colors duration-300">
                        <svg class="w-7 h-7 text-orange-600 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-white transition-colors duration-300 text-center">Fertilizer Suggestor</h3>
                </div>
            </a>

            <!-- Disease Scan Card -->
            <a href="{{ route('disease.scan') }}" class="group relative overflow-hidden bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6 flex flex-col items-center justify-center min-h-[160px] space-y-3">
                    <div class="w-14 h-14 rounded-full bg-red-100 group-hover:bg-white flex items-center justify-center transition-colors duration-300">
                        <svg class="w-7 h-7 text-red-600 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-white transition-colors duration-300 text-center">Disease Scan</h3>
                </div>
            </a>

        </div>
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
