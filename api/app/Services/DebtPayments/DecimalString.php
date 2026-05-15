<?php

namespace App\Services\DebtPayments;

final class DecimalString
{
    public static function normalize(mixed $value): string
    {
        $raw = trim(str_replace(["\xc2\xa0", ' '], '', (string) ($value ?? '0')));
        $raw = str_replace(',', '.', $raw);

        if (! preg_match('/^-?\d+(?:\.\d+)?$/', $raw)) {
            return '0.00';
        }

        $negative = str_starts_with($raw, '-');
        $raw = ltrim($raw, '-');
        [$whole, $fraction] = array_pad(explode('.', $raw, 2), 2, '');
        $whole = ltrim($whole, '0');
        $whole = $whole === '' ? '0' : $whole;
        $fraction = str_pad(substr($fraction, 0, 2), 2, '0');

        return ($negative && $whole !== '0' ? '-' : '') . $whole . '.' . $fraction;
    }

    public static function equals(string $left, string $right): bool
    {
        return bccomp(self::normalize($left), self::normalize($right), 2) === 0;
    }
}
