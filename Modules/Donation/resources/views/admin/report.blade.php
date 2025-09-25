<x-app-layout title="Donation Report">
    <x-headings.page-title title="Donation Report" />
    <x-headings.section-title title="Statistics & Summary" />

    <div class="mt-4 max-w-lg mx-auto bg-white shadow p-6 rounded space-y-4">
        <p><strong>Total Donations:</strong> {{ $totalDonations }} BDT</p>
        <p><strong>Completed Donations:</strong> {{ $completedCount }}</p>
        <p><strong>Pending Donations:</strong> {{ $pendingCount }}</p>
    </div>
</x-app-layout>