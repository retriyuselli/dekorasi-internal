<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsMasterData;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToCompany, HasFactory, LogsMasterData, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'phone',
        'email',
        'address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CustomerType::class,
        ];
    }

    /**
     * @return HasOne<Prospect, $this>
     */
    public function prospect(): HasOne
    {
        return $this->hasOne(Prospect::class);
    }
}
