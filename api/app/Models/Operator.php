<?php

namespace App\Models;

use Database\Factories\OperatorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

final class Operator extends Authenticatable
{
    /** @use HasFactory<OperatorFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'name',
        'role',
        'token',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
