<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DebtPaymentLink extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_SENT = 'sent';
    public const STATUS_OPENED = 'opened';
    public const STATUS_PAYMENT_CREATED = 'payment_created';
    public const STATUS_PAID = 'paid';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_ERROR = 'error';

    protected $fillable = [
        'campaign_id',
        'token',
        'bitrix_user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'contract_id',
        'contract_number',
        'contract_guid',
        'invoice_id',
        'invoice_number',
        'invoice_guid',
        'amount',
        'currency',
        'status',
        'expires_at',
        'opened_at',
        'paid_at',
        'cancelled_at',
        'bitrix_order_id',
        'payment_url',
        'reminders_count',
        'last_reminded_at',
        'next_remind_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expires_at' => 'immutable_datetime',
            'opened_at' => 'immutable_datetime',
            'paid_at' => 'immutable_datetime',
            'cancelled_at' => 'immutable_datetime',
            'last_reminded_at' => 'immutable_datetime',
            'next_remind_at' => 'immutable_datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DebtPaymentCampaign::class, 'campaign_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(DebtPaymentNotification::class);
    }
}
