<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpsertSeoMetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nullableFields = ['title', 'description', 'canonical', 'og_title', 'og_description', 'og_image'];
        $normalized = [
            'page_type' => trim((string) $this->input('page_type')),
            'page_slug' => trim((string) $this->input('page_slug')),
        ];

        foreach ($nullableFields as $field) {
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
        $id = (int) $this->route('id');

        return [
            'page_type' => ['required', Rule::in(['warehouse', 'box'])],
            'page_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('seo_meta')->where(fn ($query) => $query
                    ->where('page_type', $this->input('page_type'))
                    ->where('page_slug', $this->input('page_slug')))
                    ->ignore($id),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'canonical' => ['nullable', 'url', 'max:2048'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
