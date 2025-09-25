<?php

namespace Modules\Donation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // allow all authenticated & guest users
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_name'   => 'nullable|string|max:255',
            'donor_email'  => 'nullable|email|max:255',
            'donor_phone'  => 'nullable|string|max:20',
            'amount'       => 'required|numeric|min:10',
            'currency'     => 'required|string|max:10',
            'message'      => 'nullable|string|max:1000',
            'anonymous'    => 'boolean',
            'payment_gateway' => 'required|string|in:bkash,nagad,sslcommerz',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Donation amount is required.',
            'amount.min'      => 'Minimum donation is 10 BDT.',
            'payment_gateway.in' => 'Invalid payment gateway selected.',
        ];
    }
}
