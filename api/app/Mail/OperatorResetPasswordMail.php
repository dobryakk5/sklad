<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class OperatorResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $resetUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Сброс пароля — АльфаСклад Админ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.operator-reset-password',
        );
    }
}
