<?php

use App\Services\Cabinet\BitrixCabinetAuthClient;
use App\Services\Cabinet\BitrixCabinetException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Config::set('bitrix.service_base_url', 'https://bitrix.example/local/api/cabinet');
    Config::set('bitrix.service_secret', 'test-secret');
});

it('rejects successful auth bridge responses without a valid user id', function () {
    Http::fake([
        'bitrix.example/*' => Http::response([
            'name' => 'Broken User',
            'email' => 'broken@example.com',
            'phone' => '+79990000000',
        ]),
    ]);

    expect(fn () => app(BitrixCabinetAuthClient::class)->login('broken@example.com', 'password'))
        ->toThrow(BitrixCabinetException::class);
});

it('formats valid auth bridge responses', function () {
    Http::fake([
        'bitrix.example/*' => Http::response([
            'user_id' => 42,
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'phone' => '+79990000001',
        ]),
    ]);

    $user = app(BitrixCabinetAuthClient::class)->login('valid@example.com', 'password');

    expect($user)->toBe([
        'id' => 42,
        'name' => 'Valid User',
        'email' => 'valid@example.com',
        'phone' => '+79990000001',
    ]);
});

it('submits cabinet requests through the signed bridge', function () {
    Http::fake([
        'bitrix.example/*' => Http::response([
            'result_id' => 123,
            'web_form_id' => 20,
        ], 201),
    ]);

    $result = app(BitrixCabinetAuthClient::class)->submitRequest(42, 'question', [
        'name' => 'Valid User',
        'message' => 'Need help',
    ]);

    expect($result)->toBe([
        'result_id' => 123,
        'web_form_id' => 20,
    ]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://bitrix.example/local/api/cabinet/requests/create.php'
            && $request->hasHeader('X-Cabinet-Timestamp')
            && $request->hasHeader('X-Cabinet-Signature')
            && $request['user_id'] === 42
            && $request['type'] === 'question'
            && $request['fields']['message'] === 'Need help';
    });
});
