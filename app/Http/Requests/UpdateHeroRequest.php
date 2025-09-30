<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroRequest extends FormRequest
{
    public function authorize() { return $this->user()->can('manage settings'); }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'cta' => 'nullable|string|max:100',
            'background' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}