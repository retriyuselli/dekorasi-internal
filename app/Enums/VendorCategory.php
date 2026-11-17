<?php

namespace App\Enums;

enum VendorCategory: string
{
    case Rental = 'sewa';
    case Transport = 'transportasi';
    case Printing = 'percetakan';
    case Flowers = 'bunga';
    case Other = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::Rental => 'Sewa',
            self::Transport => 'Transportasi',
            self::Printing => 'Percetakan',
            self::Flowers => 'Bunga',
            self::Other => 'Lainnya',
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
