<?php

namespace App\Policies;

class WarehouseLocationPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Warehouse';
    }
}
