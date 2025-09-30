<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize() { return $this->user()->can('manage settings'); }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}