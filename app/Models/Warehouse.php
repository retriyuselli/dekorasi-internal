<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Database\Factories\WarehouseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    /** @use HasFactory<WarehouseFactory> */
    use BelongsToCompany, HasFactory, LogsMasterData;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Warehouse $warehouse): void {
            $warehouse->code = str($warehouse->code)->upper()->toString();
        });
    }

    /**
     * @return HasMany<WarehouseLocation, $this>
     */
    public function locations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    /**
     * @return HasMany<Stock, $this>
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function nominalTotal(): string
    {
        return number_format((float) $this->stocks()->sum('nominal'), 2, '.', '');
    }

    public function itemCount(): int
    {
        return (int) $this->stocks()->distinct()->count('item_id');
    }
}
