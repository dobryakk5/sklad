<?php

namespace App\Http\Requests\Api;

use App\Support\Bitrix\RentalModeMapper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rental_mode' => [
                'sometimes',
                'string',
                Rule::in(RentalModeMapper::apiValues()),
            ],
        ];
    }
}
