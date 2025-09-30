<x-app-layout title="Edit Success Story">
    <x-headings.page-title title="Edit Success Story" />
    <x-headings.section-title title="Update success story" />

    <div class="px-6 py-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('admin.successstories.update', $successStory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title', $successStory->title) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-200 focus:border-green-500">
            </div>

            <!-- Summary -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Summary</label>
                <textarea name="summary" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('summary', $successStory->summary) }}</textarea>
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <textarea name="content" rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content', $successStory->content) }}</textarea>
            </div>

            <!-- Author -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Author</label>
                <input type="text" name="author" value="{{ old('author', $successStory->author) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image">
                @if($successStory->image)
                    @if (Str::startsWith($successStory->image, 'http'))
                        <img src="{{ $successStory->image }}" class="mt-2 w-32 h-32 object-cover rounded">
                    @else
                        <img src="{{ asset('storage/'.$successStory->image) }}" class="mt-2 w-32 h-32 object-cover rounded">
                    @endif
                @endif
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" value="1" {{ $successStory->status ? 'checked' : '' }} class="form-checkbox">
                    <span class="ml-2 text-gray-700">Active</span>
                </label>
            </div>

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update</button>
        </form>
    </div>
</x-app-layout>
