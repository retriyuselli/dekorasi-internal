<?php

namespace App\Enums;

enum ProspectStatus: string
{
    case New = 'baru';
    case Contacted = 'dihubungi';
    case Interested = 'tertarik';
    case Lost = 'tidak_lanjut';
    case Converted = 'menjadi_pelanggan';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Dihubungi',
            self::Interested => 'Tertarik',
            self::Lost => 'Tidak lanjut',
            self::Converted => 'Menjadi pelanggan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'gray',
            self::Contacted => 'info',
            self::Interested => 'warning',
            self::Lost => 'danger',
            self::Converted => 'success',
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

    /**
     * @return array<string, string>
     */
    public static function openOptions(): array
    {
        return collect(self::cases())
            ->reject(fn (self $case): bool => $case === self::Converted)
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}
