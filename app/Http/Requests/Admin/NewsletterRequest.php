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
            'description' => ['nullable','string'],
            'content' => ['nullable','string'],
            'publication_year' => ['required','integer','min:2000','max:'.(date('Y')+1)],
            'cover_image' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:5120'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:5120'],
            'file' => ['nullable','file','mimes:pdf','max:10240'],
            'is_published' => ['nullable','boolean'],
        ];
    }
}