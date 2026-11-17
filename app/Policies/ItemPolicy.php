<?php

namespace App\Policies;

class ItemPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Warehouse';
    }
}
