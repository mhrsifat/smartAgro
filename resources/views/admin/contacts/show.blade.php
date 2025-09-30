<x-app-layout title="Contact Message">

    <x-headings.page-title title="Contact Message" />
    <x-headings.section-title title="Message details" />

    <div class="mt-6 max-w-3xl">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold">{{ $contact->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                    <p class="mt-2 text-sm text-gray-600">Subject: <strong>{{ $contact->subject ?? '—' }}</strong></p>
                </div>

                <div class="text-right">
                    @if($contact->replied)
                        <div class="text-sm text-green-700 font-semibold">Replied</div>
                        <div class="text-xs text-gray-500">by {{ optional($contact->replier)->name ?? '—' }}</div>
                        <div class="text-xs text-gray-500">{{ optional($contact->replied_at)->format('d M Y H:i') }}</div>
                    @else
                        <form action="{{ route('admin.contacts.replied', $contact->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 rounded bg-green-600 text-white">Mark as Replied</button>
                        </form>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($contact->message)) !!}
            </div>

            <div class="mt-6 flex gap-2">
    <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 rounded border">Back to list</a>

    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?')">
        @csrf
        @method('DELETE')
        <button class="px-4 py-2 rounded bg-red-100 text-red-700">Delete</button>
    </form>

    @unless($contact->replied)
        <a href="{{ route('admin.contacts.reply.form', $contact->id) }}" 
           class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
            Reply
        </a>
    @endunless
</div>
        </div>
    </div>

</x-app-layout>