<x-app-layout title="Success Story Details">
    <x-headings.page-title title="Success Story Details" />
    <x-headings.section-title title="{{ $successStory->title }}" />

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <strong>Author:</strong> {{ $successStory->author ?? '-' }}
        </div>
        <div class="mb-4">
            <strong>Status:</strong>
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $successStory->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $successStory->status ? 'Active' : 'Inactive' }}
            </span>
        </div>
        @if($successStory->image)
            <div class="mb-4">
                @if (Str::startsWith($successStory->image, 'http'))
                    <img src="{{ $successStory->image }}" class="w-64 h-64 object-cover rounded">
                @else
                    <img src="{{ asset('storage/'.$successStory->image) }}" class="w-64 h-64 object-cover rounded">
                @endif
            </div>
        @endif
        <div class="mb-4">
            <strong>Summary:</strong>
            <p>{{ $successStory->summary }}</p>
        </div>
        <div>
            <strong>Content:</strong>
            <p>{!! nl2br(e($successStory->content)) !!}</p>
        </div>
    </div>
</x-app-layout>
