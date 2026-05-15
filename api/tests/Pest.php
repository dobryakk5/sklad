<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function createDebtPaymentLink(array $attributes = []): \App\Models\DebtPaymentLink
{
    $campaign = \App\Models\DebtPaymentCampaign::create([
        'source' => 'monthly_debt',
        'campaign_date' => '2026-06-01',
        'status' => \App\Models\DebtPaymentCampaign::STATUS_DONE,
    ]);

    return \App\Models\DebtPaymentLink::create(array_merge([
        'campaign_id' => $campaign->id,
        'token' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        'bitrix_user_id' => 123,
        'customer_name' => 'Иванов Иван',
        'customer_email' => 'client@example.ru',
        'customer_phone' => '+79990000000',
        'contract_number' => '5/23625',
        'invoice_id' => 260726,
        'invoice_number' => '260726',
        'amount' => '208800.00',
        'currency' => 'RUB',
        'status' => \App\Models\DebtPaymentLink::STATUS_NEW,
        'expires_at' => now()->addDays(10),
    ], $attributes));
}
