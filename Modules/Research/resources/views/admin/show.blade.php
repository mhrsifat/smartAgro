@php
    $isEdit = isset($research);
@endphp

<x-app-layout title="{{ $research->title }}">
    <x-headings.page-title :title="$research->title" />

    <div class="px-6 py-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="flex flex-col md:flex-row md:space-x-6">
            <!-- Image -->
            @if($research->image)
                <img src="{{ asset('storage/' . $research->image) }}" alt="{{ $research->title }}" class="w-full md:w-1/3 rounded-lg object-cover mb-4 md:mb-0">
            @endif

            <div class="flex-1">
                <!-- Title -->
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-2">{{ $research->title }}</h2>

                <!-- Authors -->
                @if($research->authors)
                    <p class="text-gray-600 dark:text-gray-400 mb-2"><strong>Authors:</strong> {{ $research->authors }}</p>
                @endif

                <!-- Status -->
                <p class="mb-2">
                    <span class="px-2 py-1 font-semibold leading-tight 
                        @if($research->status == 'published') 
                            text-green-700 bg-green-100 dark:text-green-100 dark:bg-green-700
                        @elseif($research->status == 'under_review') 
                            text-yellow-700 bg-yellow-100 dark:text-yellow-100 dark:bg-yellow-700
                        @else 
                            text-gray-700 bg-gray-100 dark:text-gray-100 dark:bg-gray-700
                        @endif
                        rounded-full"
                    >
                        {{ ucfirst(str_replace('_', ' ', $research->status)) }}
                    </span>
                </p>

                <!-- Description -->
                <div class="mt-4 text-gray-700 dark:text-gray-200">
                    {!! nl2br(e($research->description)) !!}
                </div>

                <!-- Slug -->
                <p class="mt-4 text-gray-500 dark:text-gray-400"><strong>Slug:</strong> {{ $research->slug }}</p>

                <!-- Created At / Updated At -->
                <p class="text-gray-500 dark:text-gray-400">
                    Created: {{ $research->created_at->format('d M, Y') }} | 
                    Updated: {{ $research->updated_at->format('d M, Y') }}
                </p>

                <!-- Back Button -->
                <a href="{{ route('admin.researches.index') }}" class="inline-block mt-4 px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                    &larr; Back to List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>