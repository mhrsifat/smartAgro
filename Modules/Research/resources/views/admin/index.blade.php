<x-app-layout title="Researches">

    <x-headings.page-title title="Researches" />
    <x-headings.section-title title="All researches" />

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.researches.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Create Research</a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Authors</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Featured</th>
                    <th class="px-4 py-2 text-left">Owner</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($researches as $research)
                    <tr>
                        <td class="px-4 py-2">{{ $research->id }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.researches.show', $research->id) }}" class="hover:underline">
                                {{ $research->title }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @php
                                $authors = is_string($research->authors) ? json_decode($research->authors, true) : $research->authors;
                            @endphp
                            {{ $authors ? implode(', ', $authors) : '—' }}
                        </td>
                        <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $research->status)) }}</td>
                        <td class="px-4 py-2">{{ $research->is_featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-2">{{ optional($research->user)->name ?? '—' }}</td>
                        <td class="px-4 py-2 flex gap-2"><a href="{{ route('admin.researches.show', $research) }}" class="px-2 py-1 border rounded">View</a>
<a href="{{ route('admin.researches.edit', $research) }}" class="px-2 py-1 border rounded">Edit</a>
<form action="{{ route('admin.researches.destroy', $research) }}" method="POST" onsubmit="return confirm('Are you sure?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-2 py-1 border rounded text-red-600">Delete</button>
</form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No researches found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $researches->links() }}
        </div>
    </div>
</x-app-layout>