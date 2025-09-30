<x-app-layout title="Donations">
    <x-headings.page-title title="Donations Management" />
    <x-headings.section-title title="Manage all donations with advanced filtering and actions" />

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
        <div class="flex space-x-2">
            <a href="{{ route('admin.donations.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Donation
            </a>
            <a href="{{ route('admin.donations.report') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Report
            </a>
        </div>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.donations.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.donations.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Name, email, phone, transaction ID..."
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Gateway</label>
    <select name="payment_gateway" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">All Gateways</option>
        @foreach($paymentGateways as $gateway)
            <option value="{{ $gateway }}" {{ request('payment_gateway') === $gateway ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $gateway)) }}
            </option>
        @endforeach
    </select>
</div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Amount</label>
                    <input type="number" 
                           name="amount_min" 
                           value="{{ request('amount_min') }}"
                           placeholder="0"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Amount</label>
                    <input type="number" 
                           name="amount_max" 
                           value="{{ request('amount_max') }}"
                           placeholder="999999"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex space-x-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Apply Filters
                </button>
                <a href="{{ route('admin.donations.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Donations</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $donations->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $donations->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $donations->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">৳</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Amount</p>
                    <p class="text-lg font-semibold text-gray-900">৳{{ number_format($donations->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form id="bulk-form" action="{{ route('admin.donations.bulkUpdate') }}" method="POST" class="mb-4">
        @csrf
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center space-x-4">
                <div>
                    <input type="checkbox" id="select-all" class="rounded border-gray-300">
                    <label for="select-all" class="ml-2 text-sm text-gray-700">Select All</label>
                </div>
                <select name="bulk_action" class="border border-gray-300 rounded px-3 py-1 text-sm">
                    <option value="">Bulk Actions...</option>
                    <option value="mark_completed">Mark as Completed</option>
                    <option value="mark_pending">Mark as Pending</option>
                    <option value="mark_failed">Mark as Failed</option>
                    <option value="mark_cancelled">Mark as Cancelled</option>
                </select>
                <button type="submit" 
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition"
                        onclick="return confirm('Are you sure you want to perform this bulk action?')">
                    Apply
                </button>
            </div>
        </div>
    </form>

    <!-- Donations Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="header-checkbox" class="rounded border-gray-300">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       name="donation_ids[]" 
                                       value="{{ $donation->id }}" 
                                       class="donation-checkbox rounded border-gray-300"
                                       form="bulk-form">
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $donation->id }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $donation->donor_name ?? 'Guest' }}</div>
                                <div class="text-sm text-gray-500">{{ $donation->donor_email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-semibold">৳{{ number_format($donation->amount, 2) }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                    {{ $donation->payment_method === 'cash' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $donation->payment_method === 'bank_transfer' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $donation->payment_method === 'bkash' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $donation->payment_method === 'nagad' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ !in_array($donation->payment_method, ['cash', 'bank_transfer', 'bkash', 'nagad']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ str_replace('_', ' ', $donation->payment_method ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $donation->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $donation->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $donation->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    <svg class="w-1.5 h-1.5 mr-1.5 
                                        {{ $donation->status === 'completed' ? 'text-green-400' : '' }}
                                        {{ $donation->status === 'pending' ? 'text-yellow-400' : '' }}
                                        {{ $donation->status === 'failed' ? 'text-red-400' : '' }}
                                        {{ $donation->status === 'cancelled' ? 'text-gray-400' : '' }}" 
                                         fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $donation->created_at->format('M d, Y') }}</div>
                                <div class="text-gray-500">{{ $donation->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.donations.show', $donation->id) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                
                                @if($donation->status !== 'completed')
                                    <form action="{{ route('admin.donations.updateStatus', $donation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                                onclick="return confirm('Mark this donation as completed?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Complete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium mb-1">No donations found</h3>
                                    <p class="text-sm">Try adjusting your filters or add a new donation.</p>
                                    <a href="{{ route('admin.donations.create') }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add First Donation
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($donations->hasPages())
        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    @endif

    <script>
        // Handle select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.donation-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('header-checkbox').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.donation-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update select all when individual checkboxes change
        document.querySelectorAll('.donation-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.donation-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.donation-checkbox:checked');
                const selectAll = document.getElementById('select-all');
                const headerCheckbox = document.getElementById('header-checkbox');
                
                if (checkedCheckboxes.length === allCheckboxes.length) {
                    selectAll.checked = true;
                    headerCheckbox.checked = true;
                } else {
                    selectAll.checked = false;
                    headerCheckbox.checked = false;
                }
            });
        });

        // Bulk form submission validation
        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.donation-checkbox:checked');
            const bulkAction = document.querySelector('select[name="bulk_action"]').value;
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one donation.');
                return false;
            }
            
            if (!bulkAction) {
                e.preventDefault();
                alert('Please select a bulk action.');
                return false;
            }
        });
    </script>
</x-app-layout>