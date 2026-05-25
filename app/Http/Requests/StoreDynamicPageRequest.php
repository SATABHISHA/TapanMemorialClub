<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDynamicPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:20480'],
            'is_published' => ['nullable', 'boolean'],
            'show_on_home' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'menu_title' => ['nullable', 'string', 'max:255'],
            'menu_icon' => ['nullable', 'string', 'max:120'],
            'menu_sort_order' => ['nullable', 'integer', 'min:0'],
            'menu_is_active' => ['nullable', 'boolean'],
        ];
    }
}