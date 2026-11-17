<?php

namespace App\Enums;

enum StockCondition: string
{
    case Good = 'baik';
    case Repair = 'perlu_perbaikan';
    case Damaged = 'rusak';

    public function label(): string
    {
        return match ($this) {
            self::Good => 'Baik',
            self::Repair => 'Perlu perbaikan',
            self::Damaged => 'Rusak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Good => 'success',
            self::Repair => 'warning',
            self::Damaged => 'danger',
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
