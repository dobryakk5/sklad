<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DebtPaymentCampaign extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_RUNNING = 'running';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'source',
        'campaign_date',
        'status',
        'started_at',
        'finished_at',
        'failed_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'campaign_date' => 'date',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
            'failed_at' => 'immutable_datetime',
        ];
    }

    public function links(): HasMany
    {
        return $this->hasMany(DebtPaymentLink::class, 'campaign_id');
    }
}
