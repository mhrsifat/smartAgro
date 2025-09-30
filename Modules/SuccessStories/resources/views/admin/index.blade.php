<x-app-layout title="Success Stories">
    <x-headings.page-title title="Success Stories Management" />
    <x-headings.section-title title="Manage all success stories" />

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between">
        <a href="{{ route('admin.successstories.create') }}" 
           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
           Add New Success Story
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($stories as $story)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">{{ $story->id }}</td>
                        <td class="px-4 py-4">{{ $story->title }}</td>
                        <td class="px-4 py-4">{{ $story->author ?? '-' }}</td>
                        <td class="px-4 py-4">
                            @if($story->image)
                                @if (Str::startsWith($story->image, 'http'))
                                    <img src="{{ $story->image }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <img src="{{ asset('storage/'.$story->image) }}" class="w-16 h-16 object-cover rounded">
                                @endif
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $story->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $story->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 space-x-2">
                            <a href="{{ route('admin.successstories.show', $story) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.successstories.edit', $story) }}" class="text-green-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.successstories.destroy', $story) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No success stories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $stories->links() }}
    </div>
</x-app-layout>
