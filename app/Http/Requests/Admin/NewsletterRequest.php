<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->route('newsletter')?->id ?? null;
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:newsletters,slug,'.$id],
            'content' => ['nullable','string'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:5120'],
            'is_published' => ['nullable','boolean'],
        ];
    }
}