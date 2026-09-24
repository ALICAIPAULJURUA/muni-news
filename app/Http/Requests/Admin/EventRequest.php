<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->route('event')?->id ?? null;
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:events,slug,'.$id],
            'description' => ['required','string'],
            'location' => ['required','string','max:255'],
            'event_date' => ['required','date'],
            'end_date' => ['nullable','date','after:event_date'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:5120'],
            'is_online' => ['nullable','boolean'],
            'registration_link' => ['nullable','url','max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_online' => $this->boolean('is_online')]);
    }
}
