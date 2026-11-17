<?php

namespace App\Policies;

class CustomerPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Customer';
    }
}
