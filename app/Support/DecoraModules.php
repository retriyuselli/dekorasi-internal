<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

final class DecoraModules
{
    public static function projects(): bool
    {
        return Schema::hasTable('projects');
    }

    public static function quotations(): bool
    {
        return Schema::hasTable('quotations');
    }

    public static function revenue(): bool
    {
        return Schema::hasTable('payments') || Schema::hasTable('invoices');
    }

    public static function inventory(): bool
    {
        return Schema::hasTable('inventory_items');
    }
}
