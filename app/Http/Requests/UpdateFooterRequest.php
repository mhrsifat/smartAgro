<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterRequest extends FormRequest
{
    public function authorize() { return $this->user()->can('manage settings'); }

    public function rules()
    {
        return [
            'footer_text' => 'nullable|string|max:500',
            'social_links' => 'nullable|array',
            'social_links.*.provider' => 'required_with:social_links|string|max:50',
            'social_links.*.url' => 'required_with:social_links|url|max:255',
        ];
    }
}