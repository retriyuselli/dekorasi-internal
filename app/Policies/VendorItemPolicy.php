<?php

namespace App\Policies;

class VendorItemPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Vendor';
    }
}
