<?php

namespace App\Policies;

use App\Models\User;

class ActivityPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Activity';
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, mixed $model): bool
    {
        return false;
    }

    public function delete(User $user, mixed $model): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
