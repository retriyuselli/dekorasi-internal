<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->updateOrCreate(
            ['email' => 'admin@dekorasi.local'],
            [
                'name' => 'ERP Dekorasi',
                'legal_name' => 'ERP Dekorasi',
                'phone' => '081234567890',
                'address' => 'Indonesia',
            ],
        );

        $admin = User::query()->firstOrNew([
            'email' => env('ADMIN_EMAIL', 'admin@dekorasi.local'),
        ]);

        $admin->company_id = $company->id;
        $admin->name = 'Super Admin';
        $admin->is_active = true;

        if (! $admin->exists) {
            $admin->password = env('ADMIN_PASSWORD', 'password');
        }

        $admin->save();
        $admin->syncRoles([Roles::SUPER_ADMIN]);
    }
}
