<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Review extends Model
{
    protected $fillable = [
        'author_name',
        'text',
        'rating',
        'date',
        'photo_url',
        'source_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
