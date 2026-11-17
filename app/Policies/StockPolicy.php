<?php

namespace App\Policies;

class StockPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Warehouse';
    }
}
