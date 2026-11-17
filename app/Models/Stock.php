<?php

namespace App\Models;

use App\Enums\StockCondition;
use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use App\Support\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Stock extends Model
{
    use BelongsToCompany, LogsMasterData;

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'warehouse_location_id',
        'item_id',
        'quantity',
        'purchase_price',
        'nominal',
        'condition',
        'purchased_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'nominal' => 'decimal:2',
            'condition' => StockCondition::class,
            'purchased_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Stock $stock): void {
            $stock->nominal = self::calculateNominal($stock->quantity, $stock->purchase_price);

            $storedInAnotherWarehouse = self::query()
                ->where('item_id', $stock->item_id)
                ->where('warehouse_id', '!=', $stock->warehouse_id)
                ->when($stock->exists, fn ($query) => $query->whereKeyNot($stock->getKey()))
                ->exists();

            if ($storedInAnotherWarehouse) {
                throw ValidationException::withMessages([
                    'item_id' => 'Barang ini sudah tersimpan di gudang lain.',
                ]);
            }

            $duplicate = self::query()
                ->where('warehouse_id', $stock->warehouse_id)
                ->where('item_id', $stock->item_id)
                ->where('condition', $stock->condition)
                ->where('warehouse_location_id', $stock->warehouse_location_id)
                ->when($stock->exists, fn ($query) => $query->whereKeyNot($stock->getKey()))
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'item_id' => 'Barang ini sudah tercatat di penempatan dan kondisi yang sama.',
                ]);
            }
        });
    }

    public static function calculateNominal(int|float|string|null $quantity, int|float|string|null $purchasePrice): string
    {
        return number_format(Money::amount($quantity) * Money::amount($purchasePrice), 2, '.', '');
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => $this->item?->name ?? 'Isi gudang');
    }

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return BelongsTo<WarehouseLocation, $this>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    /**
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
