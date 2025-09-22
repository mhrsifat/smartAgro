<x-app-layout :title="'Edit — ' . $blog->title">
    <x-headings.page-title :title="'Edit Blog'" />

    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" value="{{ old('title', $blog->title) }}" class="mt-1 block w-full form-input" />
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" value="{{ old('slug', $blog->slug) }}" class="mt-1 block w-full form-input" />
                @error('slug') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Excerpt</label>
                <textarea name="excerpt" rows="2" class="mt-1 block w-full form-textarea">{{ old('excerpt', $blog->excerpt) }}</textarea>
                @error('excerpt') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Content</label>
                <textarea name="content" rows="8" class="mt-1 block w-full form-textarea">{{ old('content', $blog->content) }}</textarea>
                @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Author</label>
                    <select name="author_id" class="mt-1 block w-full form-select">
                        <option value="">-- Select author --</option>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}" {{ (old('author_id', $blog->author_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('author_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="mt-1 block w-full form-select">
                        <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Image</label>
                @if($blog->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$blog->image) }}" class="w-32 h-20 object-cover rounded" alt="">
                    </div>
                @endif
                <input type="file" name="image" class="mt-1 block w-full" />
                @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>