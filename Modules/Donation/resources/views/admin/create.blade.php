<x-app-layout title="Create Donation">
    <x-headings.page-title title="Create Donation" />
    <x-headings.section-title title="Add a new donation manually" />

    <form action="{{ route('admin.donations.store') }}" method="POST" class="mt-4 max-w-lg mx-auto bg-white shadow p-6 rounded">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Donor Name</label>
            <input type="text" name="donor_name" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="donor_email" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Phone</label>
            <input type="text" name="donor_phone" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Currency</label>
            <input type="text" name="currency" value="BDT" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Payment Gateway</label>
            <select name="payment_gateway" class="w-full border rounded p-2" required>
                <option value="manual">Manual</option>
                <option value="sslcommerz">SSLCommerz</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Message</label>
            <textarea name="message" class="w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Donation</button>
    </form>
</x-app-layout>