<?php

namespace App\Modules\MasterData\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MakeUniqueSlug
{
    /**
     * @param  class-string<Model>  $modelClass
     */
    public function execute(string $modelClass, string $column, string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $base = $base !== '' ? $base : 'item';
        $slug = $base;
        $suffix = 2;

        while ($modelClass::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where($column, $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
