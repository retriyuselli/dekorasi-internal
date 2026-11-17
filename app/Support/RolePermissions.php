<?php

namespace App\Support;

final class RolePermissions
{
    /**
     * @var list<string>
     */
    private const READ = ['viewAny', 'view'];

    /**
     * @var list<string>
     */
    private const WRITE = ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore'];

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return array_values(array_unique(array_merge(
            ShieldPermission::forSubject('Prospect', self::WRITE),
            ShieldPermission::forSubject('Customer', self::WRITE),
            ShieldPermission::forSubject('Vendor', self::WRITE),
            ShieldPermission::forSubject('Category', self::WRITE),
            ShieldPermission::forSubject('Unit', self::WRITE),
            ShieldPermission::forSubject('Warehouse', self::WRITE),
            ShieldPermission::forSubject('User', self::WRITE),
            ShieldPermission::forSubject('Role', self::WRITE),
            ShieldPermission::forSubject('Company', ['viewAny', 'view', 'update']),
            ShieldPermission::forSubject('Activity', self::READ),
            ShieldPermission::forSubject('CompanySettings', ['view']),
        )));
    }

    /**
     * @return array<string, list<string>>
     */
    public static function map(): array
    {
        return [
            Roles::SUPER_ADMIN => self::all(),
            Roles::DIRECTOR => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::READ),
                ShieldPermission::forSubject('Customer', self::READ),
                ShieldPermission::forSubject('Vendor', self::READ),
                ShieldPermission::forSubject('Category', self::READ),
                ShieldPermission::forSubject('Unit', self::READ),
                ShieldPermission::forSubject('Warehouse', self::READ),
                ShieldPermission::forSubject('User', self::READ),
                ShieldPermission::forSubject('Role', self::READ),
                ShieldPermission::forSubject('Company', ['viewAny', 'view', 'update']),
                ShieldPermission::forSubject('Activity', self::READ),
                ShieldPermission::forSubject('CompanySettings', ['view']),
            ))),
            Roles::SALES => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::WRITE),
                ShieldPermission::forSubject('Customer', self::WRITE),
                ShieldPermission::forSubject('Vendor', self::READ),
                ShieldPermission::forSubject('Category', self::READ),
                ShieldPermission::forSubject('Unit', self::READ),
            ))),
            Roles::PROJECT_MANAGER => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::READ),
                ShieldPermission::forSubject('Customer', self::READ),
                ShieldPermission::forSubject('Vendor', self::READ),
                ShieldPermission::forSubject('Category', self::READ),
                ShieldPermission::forSubject('Unit', self::READ),
                ShieldPermission::forSubject('Warehouse', self::READ),
            ))),
            Roles::WAREHOUSE => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::READ),
                ShieldPermission::forSubject('Customer', self::READ),
                ShieldPermission::forSubject('Vendor', self::READ),
                ShieldPermission::forSubject('Category', self::WRITE),
                ShieldPermission::forSubject('Unit', self::WRITE),
                ShieldPermission::forSubject('Warehouse', self::WRITE),
            ))),
            Roles::FINANCE => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::READ),
                ShieldPermission::forSubject('Customer', self::READ),
                ShieldPermission::forSubject('Vendor', self::READ),
                ShieldPermission::forSubject('Category', self::READ),
                ShieldPermission::forSubject('Unit', self::READ),
                ShieldPermission::forSubject('Warehouse', self::READ),
            ))),
            Roles::STAFF => array_values(array_unique(array_merge(
                ShieldPermission::forSubject('Prospect', self::READ),
                ShieldPermission::forSubject('Customer', self::READ),
                ShieldPermission::forSubject('Category', self::READ),
                ShieldPermission::forSubject('Unit', self::READ),
            ))),
        ];
    }
}
