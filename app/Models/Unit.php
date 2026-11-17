<?php

namespace App\Models;

use App\Models\Concerns\LogsMasterData;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory, LogsMasterData;

    protected $fillable = [
        'name',
        'symbol',
    ];

    protected static function booted(): void
    {
        static::saving(function (Unit $unit): void {
            $unit->symbol = str($unit->symbol)->lower()->toString();
        });
    }
}
