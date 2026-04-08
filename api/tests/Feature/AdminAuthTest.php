<?php

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs in admin operators and returns role in payload', function () {
    $operator = Operator::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => 'secret123',
    ]);

    $response = $this->postJson('/api/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'secret123',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.email', $operator->email)
        ->assertJsonPath('data.name', $operator->name)
        ->assertJsonPath('data.role', 'admin');

    expect($operator->fresh()->token)->not->toBeNull();
});

it('rejects invalid credentials', function () {
    Operator::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'secret123',
    ]);

    $this->postJson('/api/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-pass',
    ])->assertStatus(401);
});

it('invalidates token on logout', function () {
    $operator = Operator::factory()->admin()->create([
        'token' => 'logout-token',
    ]);

    $this->withHeader('Authorization', 'Bearer logout-token')
        ->postJson('/api/admin/logout')
        ->assertOk();

    expect($operator->fresh()->token)->toBeNull();

    $this->withHeader('Authorization', 'Bearer logout-token')
        ->getJson('/api/admin/seo')
        ->assertStatus(401);
});
