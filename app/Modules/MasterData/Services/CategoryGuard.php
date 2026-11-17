<?php

namespace App\Modules\MasterData\Services;

use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryGuard
{
    public function assertValidParent(Category $category): void
    {
        if (blank($category->parent_id)) {
            return;
        }

        if ($category->exists && (int) $category->parent_id === (int) $category->getKey()) {
            throw ValidationException::withMessages([
                'parent_id' => 'Kategori tidak boleh menjadi induk dirinya sendiri.',
            ]);
        }

        $parentId = (int) $category->parent_id;
        $seen = [];

        while ($parentId) {
            if (isset($seen[$parentId]) || ($category->exists && $parentId === (int) $category->getKey())) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Induk kategori membentuk lingkaran dan tidak dapat disimpan.',
                ]);
            }

            $seen[$parentId] = true;
            $parentId = (int) Category::query()->whereKey($parentId)->value('parent_id');
        }
    }

    public function assertDeletable(Category $category): void
    {
        if ($category->children()->exists()) {
            throw ValidationException::withMessages([
                'parent_id' => 'Kategori masih memiliki subkategori dan tidak dapat dihapus.',
            ]);
        }
    }
}
