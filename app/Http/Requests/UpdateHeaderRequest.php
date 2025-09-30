<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeaderRequest extends FormRequest
{
    public function authorize() { return $this->user()->can('manage settings'); }

    public function rules()
    {
        return [
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:4096',
            'nav_links' => 'nullable|array',
            'nav_links.*.title' => 'required_with:nav_links|string|max:100',
            'nav_links.*.url' => 'required_with:nav_links|url|max:255',
        ];
    }
}