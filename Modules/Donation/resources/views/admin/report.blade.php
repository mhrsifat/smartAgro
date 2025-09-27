<x-app-layout title="Donations Report">
    <x-headings.page-title title="Donations Report" />
    <x-headings.section-title title="Analyze donation trends and statistics" />

    <!-- Filter Form -->
    <div class="mb-6 bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.donations.report') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex justify-between items-start">
                <label for="period" class="block text-sm font-medium text-gray-700">Period</label>
                <select name="period" id="period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <div id="custom-date-range" class="{{ $period === 'custom' ? '' : 'hidden' }} flex items-end space-x-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Generate Report
            </button>
        </form>
         <a href="{{ route('admin.donations.report.pdf', request()->query()) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
           target="_blank">
           Download PDF
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">
            Report for: <span class="font-bold text-blue-600">{{ $dateFrom->format('M d, Y') }} &mdash; {{ $dateTo->format('M d, Y') }}</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Completed Amount</h4>
                <p class="mt-2 text-3xl font-bold text-green-600">৳{{ number_format($completedAmount, 2) }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Completed Donations</h4>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($completedDonations) }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Average Donation</h4>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    @if($completedDonations > 0)
                        ৳{{ number_format($completedAmount / $completedDonations, 2) }}
                    @else
                        ৳0.00
                    @endif
                </p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Other Stats</h4>
                <div class="mt-2 space-y-1 text-sm">
                    <p>Total Donations: <span class="font-semibold">{{ $totalDonations }}</span></p>
                    <p>Pending: <span class="font-semibold">{{ $pendingDonations }}</span></p>
                    <p>Failed: <span class="font-semibold">{{ $failedDonations }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Over Time Chart -->
    <div class="mb-6 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Donations Over Time</h3>
        <canvas id="donationsChart"></canvas>
    </div>

    <!-- Breakdown by Status & Gateway -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Breakdown by Status</h3>
            <div class="space-y-3">
                @forelse($statusBreakdown as $status)
                    <div class="flex justify-between items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                            {{ $status->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $status->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $status->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $status->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $status->status }}
                        </span>
                        <span class="text-sm text-gray-700">{{ $status->count }} donations</span>
                        <span class="text-sm font-semibold text-gray-900">৳{{ number_format($status->total, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No data for this period.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Breakdown by Payment Gateway</h3>
            <div class="space-y-3">
                @forelse($paymentGatewayBreakdown as $gateway)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $gateway->payment_gateway) }}</span>
                        <span class="text-sm text-gray-700">{{ $gateway->count }} donations</span>
                        <span class="text-sm font-semibold text-gray-900">৳{{ number_format($gateway->total, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No data for this period.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Donors -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900">Top Donors</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donated</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topDonors as $donor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                <div class="font-medium">{{ $donor->donor_name }}</div>
                                <div class="text-gray-500">{{ $donor->donor_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $donor->donation_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">৳{{ number_format($donor->total_donated, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No donor data available for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle custom date range
        document.getElementById('period').addEventListener('change', function () {
            const customDateRange = document.getElementById('custom-date-range');
            if (this.value === 'custom') {
                customDateRange.classList.remove('hidden');
            } else {
                customDateRange.classList.add('hidden');
            }
        });

        // Chart.js Configuration
        const dailyData = @json($dailyBreakdown);

        if (dailyData && dailyData.length > 0) {
            const labels = dailyData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            const amounts = dailyData.map(item => item.total);

            const data = {
                labels: labels,
                datasets: [{
                    label: 'Total Donation Amount',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    data: amounts,
                    fill: true,
                    tension: 0.4,
                }]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) { return 'Amount: ৳' + context.parsed.y.toFixed(2); }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return '৳' + value; }
                            }
                        }
                    }
                },
            };

            new Chart(document.getElementById('donationsChart'), config);
        }
    </script>
    @endpush
</x-app-layout>