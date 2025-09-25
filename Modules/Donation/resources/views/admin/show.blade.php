<x-app-layout title="Donation #{{ $donation->id }}">
    <x-headings.page-title title="Donation Details" />
    <x-headings.section-title title="View and update donation" />

    <div class="bg-white shadow rounded p-6 max-w-2xl mx-auto mt-4">
        <ul class="mb-4">
            <li><strong>Donor:</strong> {{ $donation->donor_name ?? 'Guest' }}</li>
            <li><strong>Email:</strong> {{ $donation->donor_email }}</li>
            <li><strong>Phone:</strong> {{ $donation->donor_phone }}</li>
            <li><strong>Amount:</strong> {{ $donation->amount }} {{ $donation->currency }}</li>
            <li><strong>Payment Gateway:</strong> {{ $donation->payment_gateway }}</li>
            <li><strong>Status:</strong> {{ $donation->status }}</li>
            <li><strong>Message:</strong> {{ $donation->message }}</li>
        </ul>

        <form action="{{ route('admin.donations.updateStatus', $donation->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <label class="block mb-2">Update Status</label>
            <select name="status" class="border rounded p-2 mb-4">
                @foreach(['pending','completed','failed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected($donation->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </form>
    </div>
</x-app-layout>