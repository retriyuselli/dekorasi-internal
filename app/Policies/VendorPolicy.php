<?php

namespace App\Policies;

class VendorPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Vendor';
    }
}
