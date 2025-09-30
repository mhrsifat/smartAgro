<x-app-layout title="Blogs">
    <x-headings.page-title :title="'Blogs'" />

    <div class="mb-4 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.blogs.create') }}"
               class="inline-block px-4 py-2 mx-2 bg-green-600 text-white rounded shadow hover:bg-green-700">
               + Create Blog
            </a>
        </div>
        <div>
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex items-center">
                <input name="q" value="{{ request('q') }}" placeholder="Search title/author..." class="form-input" />
                <button class="ml-2 px-3 py-1 bg-gray-200 rounded">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="w-full min-w-max divide-y">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm text-gray-600">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Author</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Created</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($blogs as $blog)
                    <tr class="text-sm text-gray-700">
                        <td class="px-4 py-3">{{ $blog->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.blogs.show', $blog->id) }}" class="font-semibold hover:underline">
                                {{ $blog->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ optional($blog->author)->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($blog->image)
                             @if(Str::startsWith($blog->image, ['http://', 'https://']))
                                <img src="{{ $blog->image }}" class="w-16 h-10 object-cover rounded" alt="thumb">
                            @else
                                <img src="{{ asset('storage/'.$blog->image) }}" class="w-16 h-10 object-cover rounded" alt="thumb">
                            @endif
                            @else
                                <span class="text-xs text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $blog->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('admin.blogs.show', $blog->id) }}" class="px-2 py-1 text-sm border rounded">View</a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="px-2 py-1 text-sm bg-yellow-100 border rounded">Edit</a>

                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Delete this blog?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-sm bg-red-100 border rounded">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-600">No blogs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $blogs->withQueryString()->links() }}
    </div>
</x-app-layout>