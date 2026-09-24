<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->route('download')?->id ?? null;
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:downloads,slug,'.$id],
            'description' => ['nullable','string'],
            'category' => ['required','string','max:100'],
            'file' => [($this->route('download') ? 'nullable' : 'required'),'file','max:10240'],
        ];
    }
}
