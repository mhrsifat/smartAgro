<x-app-layout title="Create Donation">
    <x-headings.page-title title="Create Donation" />
    <x-headings.section-title title="Add a new donation manually" />

    <!-- Action buttons -->
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.donations.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Donations
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.donations.store') }}" method="POST" 
          class="mt-4 max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        @csrf

        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-medium text-gray-900">Donation Information</h3>
        </div>

        <div class="p-6 space-y-6">
            <!-- Donor Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="donor_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Donor Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="donor_name" 
                           id="donor_name"
                           value="{{ old('donor_name') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('donor_name') border-red-500 @enderror"
                           required>
                    @error('donor_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="donor_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="donor_email" 
                           id="donor_email"
                           value="{{ old('donor_email') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('donor_email') border-red-500 @enderror"
                           required>
                    @error('donor_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="donor_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                </label>
                <input type="text" 
                       name="donor_phone" 
                       id="donor_phone"
                       value="{{ old('donor_phone') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('donor_phone') border-red-500 @enderror"
                       placeholder="+880 1234 567890">
                @error('donor_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Information Section -->
            <div class="border-t pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Payment Details</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Donation Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">৳</span>
                            <input type="number" 
                                   name="amount" 
                                   id="amount"
                                   value="{{ old('amount') }}"
                                   min="1"
                                   step="0.01"
                                   class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                                   required>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" 
                                id="payment_method"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-500 @enderror"
                                required>
                            <option value="">Select Payment Method</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="sslcommerz" {{ old('payment_method') === 'sslcommerz' ? 'selected' : '' }}>SSLCommerz</option>
                            <option value="bkash" {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction ID
                        </label>
                        <input type="text" 
                               name="transaction_id" 
                               id="transaction_id"
                               value="{{ old('transaction_id') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('transaction_id') border-red-500 @enderror"
                               placeholder="Auto-generated if empty">
                        @error('transaction_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="pending" {{ old('status', 'completed') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status', 'completed') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="donated_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Donation Date
                    </label>
                    <input type="datetime-local" 
                           name="donated_at" 
                           id="donated_at"
                           value="{{ old('donated_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('donated_at') border-red-500 @enderror">
                    @error('donated_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="border-t pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Additional Information</h4>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes/Comments
                    </label>
                    <textarea name="notes" 
                              id="notes"
                              rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Any additional notes about this donation...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
            <a href="{{ route('admin.donations.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Donation
                </span>
            </button>
        </div>
    </form>
</x-app-layout>