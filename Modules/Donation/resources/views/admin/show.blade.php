<x-app-layout title="Donation #{{ $donation->id }}">
    <x-headings.page-title title="Donation Details" />
    <x-headings.section-title title="View and manage donation information" />

    <!-- Navigation -->
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.donations.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Donations
        </a>
        
        <div class="flex space-x-2">
            <!-- Print Button -->
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
            
            <!-- Email Button -->
            <a href="mailto:{{ $donation->donor_email }}?subject=Regarding your donation #{{ $donation->id }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Email Donor
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Donation Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Donation #{{ $donation->id }}
                        </h3>
                        <div class="flex items-center space-x-2">
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
                        </div>
                    </div>
                </div>

                <!-- Donation Information -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Donor Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide mb-4">Donor Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Full Name</label>
                                <p class="text-sm text-gray-900">{{ $donation->donor_name ?? 'Anonymous' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email Address</label>
                                <p class="text-sm text-gray-900">
                                    @if($donation->donor_email)
                                        <a href="mailto:{{ $donation->donor_email }}" class="text-blue-600 hover:underline">
                                            {{ $donation->donor_email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Phone Number</label>
                                <p class="text-sm text-gray-900">
                                    @if($donation->donor_phone)
                                        <a href="tel:{{ $donation->donor_phone }}" class="text-blue-600 hover:underline">
                                            {{ $donation->donor_phone }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Payment Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide mb-4">Payment Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Amount</label>
                                <p class="text-2xl font-bold text-gray-900">৳{{ number_format($donation->amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Payment Method</label>
                                <p class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                        {{ $donation->payment_method === 'cash' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $donation->payment_method === 'bank_transfer' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $donation->payment_method === 'bkash' ? 'bg-pink-100 text-pink-800' : '' }}
                                        {{ $donation->payment_method === 'nagad' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ !in_array($donation->payment_method, ['cash', 'bank_transfer', 'bkash', 'nagad']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ str_replace('_', ' ', $donation->payment_method ?? 'N/A') }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Transaction ID</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $donation->transaction_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Donation Date</label>
                                <p class="text-sm text-gray-900">
                                    {{ $donation->donated_at ? $donation->donated_at->format('M d, Y \a\t h:i A') : $donation->created_at->format('M d, Y \a\t h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($donation->notes)
                        <hr class="border-gray-200">
                        
                        <!-- Notes -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide mb-4">Notes</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700">{{ $donation->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($donation->admin_notes)
                        <hr class="border-gray-200">
                        
                        <!-- Admin Notes -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide mb-4">Admin Notes</h4>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <p class="text-sm text-gray-700">{{ $donation->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <hr class="border-gray-200">

                    <!-- Timestamps -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wide mb-4">Timeline</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500">Created</span>
                                <span class="text-sm text-gray-900">{{ $donation->created_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500">Last Updated</span>
                                <span class="text-sm text-gray-900">{{ $donation->updated_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>
                            @if($donation->status_updated_at)
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500">Status Updated</span>
                                    <span class="text-sm text-gray-900">{{ $donation->status_updated_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($donation->status !== 'completed')
                        <form action="{{ route('admin.donations.updateStatus', $donation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                    onclick="return confirm('Mark this donation as completed?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if($donation->status === 'pending')
                        <form action="{{ route('admin.donations.updateStatus', $donation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="failed">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                    onclick="return confirm('Mark this donation as failed?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Mark as Failed
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Status Update Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.donations.updateStatus', $donation) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                id="status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            @foreach(['pending', 'completed', 'failed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $donation->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" 
                                  id="admin_notes"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('admin_notes') border-red-500 @enderror"
                                  placeholder="Add notes about this status change...">{{ old('admin_notes', $donation->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Related Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Information</h3>
                <div class="space-y-4">
                    @if($donation->donor_email)
                        @php
                            $relatedDonations = \Modules\Donation\Models\Donation::where('donor_email', $donation->donor_email)
                                                ->where('id', '!=', $donation->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($relatedDonations->count() > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Other donations by this donor</h4>
                                <div class="space-y-2">
                                    @foreach($relatedDonations as $related)
                                        <div class="flex justify-between items-center text-sm">
                                            <a href="{{ route('admin.donations.show', $related) }}" 
                                               class="text-blue-600 hover:underline">
                                                #{{ $related->id }}
                                            </a>
                                            <span class="text-gray-500">৳{{ number_format($related->amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Donation Statistics</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            @php
                                $daysSinceCreated = $donation->created_at->diffInDays(now());
                            @endphp
                            <p>Created {{ $daysSinceCreated }} {{ Str::plural('day', $daysSinceCreated) }} ago</p>
                            <p>IP Address: {{ $donation->ip_address ?? 'N/A' }}</p>
                            <p>User Agent: {{ $donation->user_agent ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
        }
    </style>
</x-app-layout>