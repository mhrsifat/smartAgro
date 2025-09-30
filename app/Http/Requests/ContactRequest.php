<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Name is required.',
            'email.required'   => 'Email is required.',
            'email.email'      => 'Please enter a valid email address.',
            'message.required' => 'Please add a message.',
        ];
    }
}