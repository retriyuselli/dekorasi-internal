<?php

namespace App\Modules\MasterData\Actions;

use App\Models\Company;

class ResolveCompany
{
    public function execute(): Company
    {
        $company = Company::query()->orderBy('id')->first();

        if ($company) {
            return $company;
        }

        return Company::query()->create([
            'name' => 'ERP Dekorasi',
            'legal_name' => 'ERP Dekorasi',
            'email' => 'admin@dekorasi.local',
        ]);
    }
}
