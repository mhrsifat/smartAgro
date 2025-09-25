<x-app-layout title="Donations">
    <x-headings.page-title title="Donations List" />
    <x-headings.section-title title="Manage all donations" />

    <div class="overflow-x-auto mt-4">
        <table class="w-full table-auto border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Donor</th>
                    <th class="border px-4 py-2">Amount</th>
                    <th class="border px-4 py-2">Gateway</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                    <tr>
                        <td class="border px-4 py-2">{{ $donation->id }}</td>
                        <td class="border px-4 py-2">{{ $donation->donor_name ?? 'Guest' }}</td>
                        <td class="border px-4 py-2">{{ $donation->amount }} {{ $donation->currency }}</td>
                        <td class="border px-4 py-2">{{ $donation->payment_gateway }}</td>
                        <td class="border px-4 py-2">{{ $donation->status }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('admin.donations.show', $donation->id) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $donations->links() }}
    </div>
</x-app-layout>