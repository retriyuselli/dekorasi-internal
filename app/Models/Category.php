<?php

namespace App\Models;

use App\Models\Concerns\LogsMasterData;
use App\Modules\MasterData\Actions\MakeUniqueSlug;
use App\Modules\MasterData\Services\CategoryGuard;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, LogsMasterData;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
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
        static::saving(function (Category $category): void {
            if (blank($category->slug)) {
                $category->slug = app(MakeUniqueSlug::class)->execute(
                    self::class,
                    'slug',
                    (string) $category->name,
                    $category->exists ? $category->getKey() : null,
                );
            }

            app(CategoryGuard::class)->assertValidParent($category);
        });

        static::deleting(function (Category $category): void {
            app(CategoryGuard::class)->assertDeletable($category);
        });
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
