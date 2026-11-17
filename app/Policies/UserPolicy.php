<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'User';
    }

    public function delete(User $user, mixed $model): bool
    {
        if ($model instanceof User && $user->is($model)) {
            return false;
        }

        return parent::delete($user, $model);
    }
}
