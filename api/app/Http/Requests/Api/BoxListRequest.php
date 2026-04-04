<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BoxListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock_id'    => ['sometimes', 'integer', 'min:1'],

            // Только публично-видимые статусы из BoxStatus enum.
            // 'service' намеренно исключён: он не входит в BoxStatus и
            // BitrixBoxStatusMapper::toEnumIds() вернул бы null → фильтр не применился бы.
            'status'      => ['sometimes', 'string', 'in:free,rented,reserved,freeing_7,freeing_14'],

            'size_min'    => ['sometimes', 'numeric', 'min:0'],
            'size_max'    => ['sometimes', 'numeric', 'min:0', 'gte:size_min'],
            'object_type' => ['sometimes', 'string', 'in:Бокс,Ячейка,Контейнер,Антресольный бокс'],
            'page'        => ['sometimes', 'integer', 'min:1'],
            'per_page'    => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
