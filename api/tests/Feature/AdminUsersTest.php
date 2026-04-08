<?php

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('forbids operators from users endpoints', function () {
    $operator = Operator::factory()->create([
        'token' => 'operator-token',
        'role' => 'operator',
    ]);

    $this->withHeader('Authorization', 'Bearer '.$operator->token)
        ->getJson('/api/admin/users')
        ->assertStatus(403);
});

it('allows admin to create and update users', function () {
    $admin = Operator::factory()->admin()->create([
        'token' => 'admin-token',
        'email' => 'admin@example.com',
    ]);

    $createResponse = $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->postJson('/api/admin/users', [
            'name' => 'Content Operator',
            'email' => 'operator@example.com',
            'password' => 'secret123',
            'role' => 'operator',
        ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('data.email', 'operator@example.com')
        ->assertJsonPath('data.role', 'operator');

    $userId = $createResponse->json('data.id');

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->putJson("/api/admin/users/{$userId}", [
            'name' => 'Main Admin',
            'email' => 'operator@example.com',
            'role' => 'admin',
            'password' => '',
        ])
        ->assertOk()
        ->assertJsonPath('data.role', 'admin')
        ->assertJsonPath('data.name', 'Main Admin');
});

it('rejects duplicate emails when creating users', function () {
    $admin = Operator::factory()->admin()->create(['token' => 'admin-token']);
    Operator::factory()->create(['email' => 'duplicate@example.com']);

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->postJson('/api/admin/users', [
            'name' => 'Duplicate',
            'email' => 'duplicate@example.com',
            'password' => 'secret123',
            'role' => 'operator',
        ])
        ->assertStatus(422);
});

it('does not change password when edit password is empty', function () {
    $admin = Operator::factory()->admin()->create(['token' => 'admin-token']);
    $user = Operator::factory()->create([
        'email' => 'operator@example.com',
        'password' => 'secret123',
    ]);

    $originalHash = $user->password;

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->putJson("/api/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'operator@example.com',
            'role' => 'operator',
            'password' => '',
        ])
        ->assertOk();

    expect($user->fresh()->password)->toBe($originalHash);
});

it('prevents deleting yourself', function () {
    $admin = Operator::factory()->admin()->create(['token' => 'admin-token']);

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->deleteJson("/api/admin/users/{$admin->id}")
        ->assertStatus(422)
        ->assertJsonPath('error', 'Нельзя удалить себя');
});

it('prevents lowering own admin role', function () {
    $admin = Operator::factory()->admin()->create([
        'token' => 'admin-token',
        'email' => 'admin@example.com',
    ]);
    Operator::factory()->admin()->create(['email' => 'second-admin@example.com']);

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->putJson("/api/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'operator',
            'password' => '',
        ])
        ->assertStatus(422)
        ->assertJsonPath('error', 'Нельзя понизить собственную роль');
});

it('prevents deleting the last administrator', function () {
    $admin = Operator::factory()->admin()->create(['token' => 'admin-token']);

    $secondAdmin = Operator::factory()->admin()->create(['email' => 'second@example.com']);

    $this->withHeader('Authorization', 'Bearer '.$admin->token)
        ->deleteJson("/api/admin/users/{$secondAdmin->id}")
        ->assertOk();

    $lastAdmin = $admin->fresh();

    $this->withHeader('Authorization', 'Bearer '.$lastAdmin->token)
        ->postJson('/api/admin/users', [
            'name' => 'Operator',
            'email' => 'operator2@example.com',
            'password' => 'secret123',
            'role' => 'operator',
        ])
        ->assertCreated();

    $remainingAdmin = Operator::query()->where('role', 'admin')->sole();

    $this->withHeader('Authorization', 'Bearer '.$remainingAdmin->token)
        ->putJson("/api/admin/users/{$remainingAdmin->id}", [
            'name' => $remainingAdmin->name,
            'email' => $remainingAdmin->email,
            'role' => 'operator',
            'password' => '',
        ])
        ->assertStatus(422);
});
