<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectionPatternRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->route('pattern')?->id ?? null;
        return [
            'section_slug' => ['required', 'string', 'max:100', 'unique:section_patterns,section_slug,' . $id],
            'section_name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'opacity' => ['required', 'numeric', 'min:0', 'max:1'],
            'blend_mode' => ['required', 'string', 'in:multiply,overlay,screen,normal'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}