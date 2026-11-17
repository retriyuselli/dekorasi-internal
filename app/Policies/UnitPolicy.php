<?php

namespace App\Policies;

class UnitPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Unit';
    }
}
