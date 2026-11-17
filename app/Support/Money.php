<?php

namespace App\Support;

final class Money
{
    public static function isAmount(int|float|string|null $value): bool
    {
        if (is_int($value) || is_float($value)) {
            return true;
        }

        if (! is_string($value) || trim($value) === '') {
            return false;
        }

        return preg_match('/^-?\d{1,3}(\.\d{3})+(,\d+)?$/', trim($value)) === 1
            || preg_match('/^-?\d+(,\d+)?$/', trim($value)) === 1
            || preg_match('/^-?\d+\.\d+$/', trim($value)) === 1;
    }

    public static function amount(int|float|string|null $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim($value);

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        } elseif (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $value) === 1) {
            $value = str_replace('.', '', $value);
        }

        return (float) $value;
    }

    public static function grouped(int|float|string|null $amount): string
    {
        return number_format(self::amount($amount), 0, ',', '.');
    }

    public static function rupiah(int|float|string|null $amount): string
    {
        $normalized = number_format(self::amount($amount), 2, '.', '');
        $negative = str_starts_with($normalized, '-');
        $absolute = ltrim($normalized, '-');
        [$whole, $fraction] = array_pad(explode('.', $absolute), 2, '00');
        $decimals = (int) $fraction === 0 ? 0 : 2;
        $formatted = number_format((float) ($whole.'.'.$fraction), $decimals, ',', '.');

        return ($negative ? '-Rp' : 'Rp').$formatted;
    }
}
