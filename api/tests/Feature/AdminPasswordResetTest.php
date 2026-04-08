<?php

use App\Mail\OperatorResetPasswordMail;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('creates password reset token and sends email for existing operator', function () {
    Mail::fake();

    Operator::factory()->create(['email' => 'admin@example.com']);

    $this->postJson('/api/admin/forgot-password', [
        'email' => 'admin@example.com',
    ])->assertOk();

    expect(DB::table('operator_password_resets')->where('email', 'admin@example.com')->exists())->toBeTrue();

    Mail::assertSent(OperatorResetPasswordMail::class, function (OperatorResetPasswordMail $mail) {
        return str_contains($mail->resetUrl, '/admin/reset-password?token=')
            && str_contains($mail->resetUrl, 'email=admin%40example.com');
    });
});

it('returns neutral response for unknown email', function () {
    Mail::fake();

    $this->postJson('/api/admin/forgot-password', [
        'email' => 'missing@example.com',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Если аккаунт существует, письмо отправлено');

    expect(DB::table('operator_password_resets')->count())->toBe(0);
    Mail::assertNothingSent();
});

it('resets password with valid token and invalidates active session', function () {
    $operator = Operator::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'old-password',
        'token' => 'active-token',
    ]);

    $plainToken = 'reset-token';

    DB::table('operator_password_resets')->insert([
        'email' => 'admin@example.com',
        'token' => Hash::make($plainToken),
        'created_at' => now(),
    ]);

    $this->postJson('/api/admin/reset-password', [
        'email' => 'admin@example.com',
        'token' => $plainToken,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Пароль успешно изменён');

    expect(Hash::check('new-password', $operator->fresh()->password))->toBeTrue();
    expect($operator->fresh()->token)->toBeNull();
    expect(DB::table('operator_password_resets')->where('email', 'admin@example.com')->exists())->toBeFalse();
});

it('rejects invalid or expired reset tokens', function () {
    Operator::factory()->create(['email' => 'admin@example.com']);

    DB::table('operator_password_resets')->insert([
        'email' => 'admin@example.com',
        'token' => Hash::make('valid-token'),
        'created_at' => now()->subMinutes(61),
    ]);

    $this->postJson('/api/admin/reset-password', [
        'email' => 'admin@example.com',
        'token' => 'valid-token',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertStatus(422);

    DB::table('operator_password_resets')->insert([
        'email' => 'admin@example.com',
        'token' => Hash::make('valid-token'),
        'created_at' => now(),
    ]);

    $this->postJson('/api/admin/reset-password', [
        'email' => 'admin@example.com',
        'token' => 'invalid-token',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertStatus(422);
});
