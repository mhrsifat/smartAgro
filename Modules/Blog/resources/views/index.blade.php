<x-app-layout title="Create Blog Post">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Create Blog Post
        </h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}" 
                    required 
                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="6" 
                    required 
                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Image</label>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    accept="image/*" 
                    class="w-full mt-1 text-gray-700 dark:text-gray-200"
                >
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Publish Blog
                </button>
            </div>
        </form>
    </div>
</x-app-layout>