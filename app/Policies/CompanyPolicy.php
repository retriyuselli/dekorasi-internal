<?php

namespace App\Policies;

use App\Models\User;

class CompanyPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Company';
    }

    public function create(User $user): bool
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
