<?php

namespace App\Models\Concerns;

use App\Models\Company;
use App\Modules\MasterData\Actions\ResolveCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::creating(function (Model $model): void {
            if (blank($model->getAttribute('company_id'))) {
                $model->setAttribute('company_id', app(ResolveCompany::class)->execute()->id);
            }
        });
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
