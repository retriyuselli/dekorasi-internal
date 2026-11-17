<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorItem extends Model
{
    use BelongsToCompany, LogsMasterData;

    protected $fillable = [
        'company_id',
        'vendor_id',
        'unit_id',
        'name',
        'cost_price',
        'sell_price',
        'quantity',
        'gross_profit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
            'quantity' => 'decimal:2',
            'gross_profit' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (VendorItem $item): void {
            $item->gross_profit = self::calculateGrossProfit(
                $item->sell_price,
                $item->cost_price,
                $item->quantity,
            );
        });
    }

    public static function calculateGrossProfit(int|float|string|null $sellPrice, int|float|string|null $costPrice, int|float|string|null $quantity): string
    {
        $profit = (Money::amount($sellPrice) - Money::amount($costPrice)) * Money::amount($quantity);

        return number_format($profit, 2, '.', '');
    }

    /**
     * @return BelongsTo<Vendor, $this>
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
