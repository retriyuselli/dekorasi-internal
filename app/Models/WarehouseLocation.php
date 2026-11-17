<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class WarehouseLocation extends Model
{
    use BelongsToCompany, LogsMasterData;

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'code',
        'name',
        'notes',
    ];

    protected static function booted(): void
    {
        static::saving(function (WarehouseLocation $location): void {
            $location->code = str($location->code)->upper()->toString();
        });

        static::deleting(function (WarehouseLocation $location): void {
            if ($location->stocks()->exists()) {
                throw ValidationException::withMessages([
                    'name' => 'Penempatan masih berisi barang.',
                ]);
            }
        });
    }

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return HasMany<Stock, $this>
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
