<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use BelongsToCompany, HasFactory, LogsMasterData;

    protected $fillable = [
        'company_id',
        'category_id',
        'unit_id',
        'vendor_id',
        'code',
        'name',
        'notes',
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
        static::saving(function (Item $item): void {
            $item->code = str($item->code)->upper()->toString();
        });

        static::deleting(function (Item $item): void {
            if ($item->stocks()->exists()) {
                throw ValidationException::withMessages([
                    'name' => 'Barang masih tersimpan di gudang.',
                ]);
            }
        });
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo<Vendor, $this>
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * @return HasMany<Stock, $this>
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Barang yang belum tersimpan di gudang. Saat mengubah satu baris, barang baris itu tetap ikut.
     *
     * @param  Builder<Item>  $query
     * @return Builder<Item>
     */
    public function scopeUnassigned(Builder $query, ?int $exceptStockId = null): Builder
    {
        return $query->where(function (Builder $query) use ($exceptStockId): void {
            $query->whereDoesntHave('stocks');

            if ($exceptStockId !== null) {
                $query->orWhereHas(
                    'stocks',
                    fn (Builder $stocks): Builder => $stocks->whereKey($exceptStockId),
                );
            }
        });
    }
}
