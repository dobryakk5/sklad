<?php

use App\Services\DebtPayments\DecimalString;

it('normalizes decimal strings without float arithmetic', function () {
    expect(DecimalString::normalize('208 800,5'))->toBe('208800.50')
        ->and(DecimalString::normalize('001.234'))->toBe('1.23')
        ->and(DecimalString::equals('208800.0', '208800.00'))->toBeTrue();
});
