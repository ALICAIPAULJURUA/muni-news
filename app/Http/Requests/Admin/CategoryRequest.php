<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super_admin','comm_admin']);
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id ?? null;
        return [
            'name' => ['required','string','max:100'],
            'slug' => ['nullable','string','max:120','unique:categories,slug,'.$id],
            'description' => ['nullable','string'],
            'parent_id' => ['nullable','exists:categories,id'],
            'sort_order' => ['nullable','integer'],
        ];
    }
}
