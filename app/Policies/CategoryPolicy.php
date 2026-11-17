<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Category';
    }

    public function delete(User $user, mixed $model): bool
    {
        if ($model instanceof Category && $model->children()->exists()) {
            return false;
        }

        return parent::delete($user, $model);
    }
}
