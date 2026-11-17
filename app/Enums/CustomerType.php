<?php

namespace App\Enums;

enum CustomerType: string
{
    case Individual = 'perorangan';
    case Company = 'perusahaan';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Perorangan',
            self::Company => 'Perusahaan',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}
