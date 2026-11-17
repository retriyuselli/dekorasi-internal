<?php

namespace App\Policies;

class RolePolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Role';
    }
}
