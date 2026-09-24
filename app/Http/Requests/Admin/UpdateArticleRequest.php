<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super_admin','comm_admin','editor']);
    }

    public function rules(): array
    {
        $articleId = $this->route('article')?->id ?? $this->route('id');
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:articles,slug,'.$articleId],
            'category_id' => ['required','exists:categories,id'],
            'summary' => ['required','string'],
            'content' => ['required','string'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp,svg','max:5120'],
            'is_featured' => ['nullable','boolean'],
            'is_breaking' => ['nullable','boolean'],
            'is_published' => ['nullable','boolean'],
            'published_at' => ['nullable','date'],
            'meta_title' => ['nullable','string','max:100'],
            'meta_description' => ['nullable','string','max:200'],
            'tags' => ['nullable','array'],
            'tags.*' => ['string','max:50'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_breaking' => $this->boolean('is_breaking'),
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
