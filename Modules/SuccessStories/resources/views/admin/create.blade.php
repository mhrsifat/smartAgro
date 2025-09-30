<x-app-layout title="Create Success Story">
    <x-headings.page-title title="Create Success Story" />
    <x-headings.section-title title="Add a new success story" />

    <div class="px-6 py-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('admin.successstories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" name="title" id="title" placeholder="Enter story title"
                    value="{{ old('title') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Summary -->
            <div class="mb-4">
                <label for="summary" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Summary</label>
                <textarea name="summary" id="summary" rows="4" placeholder="Enter story summary"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Content</label>
                <textarea name="content" id="content" rows="8" placeholder="Enter full story content"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author -->
            <div class="mb-4">
                <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Author</label>
                <input type="text" name="author" id="author" placeholder="Enter author name"
                    value="{{ old('author') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                @error('author')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" value="1" class="form-checkbox">
                    <span class="ml-2 text-gray-700 dark:text-gray-200">Active</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="mt-6">
                <button type="submit"
                    class="px-4 py-2 font-semibold text-white bg-green-600 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                    Save Success Story
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
