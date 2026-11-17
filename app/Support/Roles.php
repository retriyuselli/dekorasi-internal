<?php

namespace App\Support;

final class Roles
{
    public const SUPER_ADMIN = 'Super Admin';

    public const DIRECTOR = 'Director';

    public const SALES = 'Sales';

    public const PROJECT_MANAGER = 'Project Manager';

    public const WAREHOUSE = 'Warehouse';

    public const FINANCE = 'Finance';

    public const STAFF = 'Staff';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::DIRECTOR,
            self::SALES,
            self::PROJECT_MANAGER,
            self::WAREHOUSE,
            self::FINANCE,
            self::STAFF,
        ];
    }
}
