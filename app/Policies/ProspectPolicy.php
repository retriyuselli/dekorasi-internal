<?php

namespace App\Policies;

class ProspectPolicy extends ChecksShieldPermissions
{
    protected function subject(): string
    {
        return 'Prospect';
    }
}
