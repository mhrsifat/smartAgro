<x-app-layout title="Contact Messages">

    <x-headings.page-title title="Contact Messages" />
    <x-headings.section-title title="All messages" />

    <div class="mt-6">

        {{-- Success message --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-50 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search --}}
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex gap-2">
                <input
                    type="search"
                    name="q"
                    value="{{ request('q', $q ?? '') }}"
                    placeholder="Search by name, email, subject or message..."
                    class="flex-1 rounded-md border-gray-300 px-3 py-2"
                />
                <button class="px-4 py-2 rounded-md bg-[#1976D2] text-white">Search</button>
            </form>
        </div>

        {{-- MOBILE: cards (visible on small screens) --}}
        <div class="space-y-4 md:hidden">
            @forelse($contacts as $contact)
                <article class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $contact->name }}</h3>
                                <div class="text-xs text-gray-500">
                                    @if($contact->replied)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">Replied</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <p class="mt-1 text-xs text-gray-500 truncate">{{ $contact->email }}</p>
                            <p class="mt-2 text-sm text-gray-700">
                                <strong>{{ $contact->subject ?? '—' }}</strong>
                            </p>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit($contact->message, 180) }}
                            </p>

                            @if($contact->replied)
                                <p class="mt-2 text-xs text-gray-500">
                                    by {{ optional($contact->replier)->name ?? '—' }} at {{ optional($contact->replied_at)->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- actions (mobile stacked) --}}
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.contacts.show', $contact->id) }}"
                           class="block px-3 py-2 text-center rounded border text-sm bg-gray-50 hover:bg-gray-100">
                            View
                        </a>

                        @unless($contact->replied)
                            <a href="{{ route('admin.contacts.reply.form', $contact->id) }}"
                               class="block px-3 py-2 text-center rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">
                                Reply
                            </a>

                            <form action="{{ route('admin.contacts.replied', $contact->id) }}" method="POST" class="col-span-2">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 rounded bg-green-600 text-white hover:bg-green-700 text-sm"
                                        onclick="return confirm('Mark this message as replied?')">
                                    Mark as Replied
                                </button>
                            </form>
                        @endunless

                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="col-span-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 rounded bg-red-100 text-red-700 hover:bg-red-200 text-sm"
                                    onclick="return confirm('Delete this message permanently?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="text-center text-gray-500 py-8">No messages found.</div>
            @endforelse
        </div>

        {{-- DESKTOP: table (hidden on small screens) --}}
        <div class="hidden md:block overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Subject</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Message</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $contact->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $contact->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $contact->subject ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($contact->message, 120) }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($contact->replied)
                                    <div class="text-sm text-green-700 font-semibold">Replied</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        by {{ optional($contact->replier)->name ?? '—' }} at {{ optional($contact->replied_at)->format('d M Y H:i') }}
                                    </div>
                                @else
                                    <div class="text-sm text-yellow-700 font-semibold">Pending</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center space-x-1">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">View</a>

                                    @unless($contact->replied)
                                        <a href="{{ route('admin.contacts.reply.form', $contact->id) }}" class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">Reply</a>

                                        <form action="{{ route('admin.contacts.replied', $contact->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white hover:bg-green-700 text-sm"
                                                    onclick="return confirm('Mark this message as replied?')">Mark as Replied</button>
                                        </form>
                                    @endunless

                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this message permanently?')" class="px-3 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        <div class="mt-4">
            {{ $contacts->withQueryString()->links() }}
        </div>

    </div>

</x-app-layout>