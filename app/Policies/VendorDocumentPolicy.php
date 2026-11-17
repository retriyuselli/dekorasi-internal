<?php

namespace App\Policies;

class VendorDocumentPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Vendor';
    }
}
