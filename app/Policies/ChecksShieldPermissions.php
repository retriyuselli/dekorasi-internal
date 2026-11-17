<?php

namespace App\Policies;

use App\Models\User;
use App\Support\ShieldPermission;

abstract class ChecksShieldPermissions
{
    abstract protected function subject(): string;

    protected function allows(User $user, string $ability): bool
    {
        return $user->can(ShieldPermission::make($ability, $this->subject()));
    }

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'viewAny');
    }

    public function view(User $user, mixed $model): bool
    {
        return $this->allows($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create');
    }

    public function update(User $user, mixed $model): bool
    {
        return $this->allows($user, 'update');
    }

    public function delete(User $user, mixed $model): bool
    {
        return $this->allows($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allows($user, 'deleteAny');
    }

    public function restore(User $user, mixed $model): bool
    {
        return $this->allows($user, 'restore');
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return $this->allows($user, 'delete');
    }
}
