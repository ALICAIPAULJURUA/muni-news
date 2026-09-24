<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id ?? null;
        return [
            'username' => ['required','string','max:50', Rule::unique('users','username')->ignore($id)],
            'full_name' => ['required','string','max:150'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($id)],
            'password' => [$id ? 'nullable' : 'required','string','min:8','confirmed'],
            'role' => ['required','string','in:super_admin,comm_admin,editor,viewer'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', true)]);
    }
}
