<x-app-layout title="Reply to Message">

    <x-headings.page-title title="Reply to {{ $contact->name }}" />
    <x-headings.section-title title="Original message" />

    <div class="mt-6 max-w-3xl space-y-4">

        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-700"><strong>From:</strong> {{ $contact->name }} ({{ $contact->email }})</p>
            <p class="text-gray-700 mt-2"><strong>Subject:</strong> {{ $contact->subject ?? '—' }}</p>
            <hr class="my-3">
            <p class="text-gray-700 whitespace-pre-line">{{ $contact->message }}</p>
        </div>

        <form action="{{ route('admin.contacts.reply.send', $contact->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Reply Message</label>
                <textarea name="message" rows="6" required
                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#1976D2] focus:ring-[#1976D2] @error('message') border-red-400 ring-red-100 @enderror"
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Send Reply</button>
                <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-100">Cancel</a>
            </div>
        </form>

    </div>

</x-app-layout>