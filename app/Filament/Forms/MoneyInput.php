<?php

namespace App\Filament\Forms;

use App\Support\Money;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

final class MoneyInput
{
    public static function make(string $name): TextInput
    {
        return TextInput::make($name)
            ->prefix('Rp')
            ->inputMode('numeric')
            ->mask(RawJs::make("\$money(\$input, ',', '.', 0)"))
            ->formatStateUsing(fn (mixed $state): ?string => ($state === null || $state === '') ? null : Money::grouped($state))
            ->dehydrateStateUsing(fn (mixed $state): ?string => ($state === null || $state === '') ? null : number_format(Money::amount($state), 2, '.', ''))
            ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                if ($value === null || $value === '') {
                    return;
                }

                if (! Money::isAmount($value)) {
                    $fail('Masukkan angka yang valid.');

                    return;
                }

                if (Money::amount($value) < 0) {
                    $fail('Nilai tidak boleh kurang dari nol.');
                }
            });
    }
}
