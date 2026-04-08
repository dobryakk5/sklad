<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpsertReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [
            'author_name' => trim((string) $this->input('author_name')),
            'text' => trim((string) $this->input('text')),
        ];

        foreach (['photo_url', 'source_url'] as $field) {
            $value = $this->input($field);
            $normalized[$field] = is_string($value) ? trim($value) : $value;
            if ($normalized[$field] === '') {
                $normalized[$field] = null;
            }
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'date' => ['required', 'date'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
