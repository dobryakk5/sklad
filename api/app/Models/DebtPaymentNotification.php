<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DebtPaymentNotification extends Model
{
    public const CHANNEL_EMAIL = 'email';
    public const CHANNEL_SMS = 'sms';

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENDING = 'sending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'debt_payment_link_id',
        'channel',
        'recipient',
        'subject',
        'body',
        'status',
        'provider',
        'provider_message_id',
        'provider_response',
        'error_message',
        'queued_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'queued_at' => 'immutable_datetime',
            'sent_at' => 'immutable_datetime',
        ];
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(DebtPaymentLink::class, 'debt_payment_link_id');
    }
}
