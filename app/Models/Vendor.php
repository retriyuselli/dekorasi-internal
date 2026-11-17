<?php

namespace App\Models;

use App\Enums\VendorCategory;
use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    /** @use HasFactory<VendorFactory> */
    use BelongsToCompany, HasFactory, LogsMasterData, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'category',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'category' => VendorCategory::class,
        ];
    }

    /**
     * @return HasMany<VendorItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(VendorItem::class);
    }

    /**
     * @return HasMany<VendorDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function grossProfitTotal(): string
    {
        return number_format((float) $this->items()->sum('gross_profit'), 2, '.', '');
    }
}
