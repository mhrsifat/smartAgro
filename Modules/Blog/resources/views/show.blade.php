<x-app-layout :title="$blog->title">
    <x-headings.page-title :title="$blog->title" />

    <div class="bg-white p-6 rounded shadow space-y-6">
        <div class="flex items-start gap-6">
            @if($blog->image)
                <img src="{{ asset('storage/'.$blog->image) }}" class="w-48 h-32 object-cover rounded" alt="">
            @endif
            <div>
                <p class="text-sm text-gray-500">By: {{ optional($blog->author)->name ?? '—' }}</p>
                <p class="text-sm text-gray-500">Status: <strong>{{ ucfirst($blog->status) }}</strong></p>
                <p class="text-sm text-gray-500">Created: {{ $blog->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-2">{{ $blog->title }}</h2>
            @if($blog->excerpt)
                <p class="text-gray-700 mb-4">{{ $blog->excerpt }}</p>
            @endif

            <div class="prose max-w-none">
                {!! $blog->content !!}
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="px-3 py-1 bg-yellow-100 border rounded">Edit</a>
            <a href="{{ route('admin.blogs.index') }}" class="px-3 py-1 border rounded">Back</a>
        </div>
    </div>
</x-app-layout>