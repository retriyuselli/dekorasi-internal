<?php

namespace App\Policies;

class WarehousePolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Warehouse';
    }
}
