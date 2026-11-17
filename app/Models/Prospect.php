<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Enums\ProspectStatus;
use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Database\Factories\ProspectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    /** @use HasFactory<ProspectFactory> */
    use BelongsToCompany, HasFactory, LogsMasterData, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'phone',
        'email',
        'address',
        'event_interest',
        'status',
        'notes',
        'customer_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => CustomerType::class,
            'status' => ProspectStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Prospect $prospect): void {
            if ($prospect->customer_id) {
                $prospect->status = ProspectStatus::Converted;
            }
        });
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
