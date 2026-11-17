<?php

namespace App\Modules\MasterData\Actions;

use App\Models\Company;
use App\Modules\MasterData\DTOs\CompanyData;
use Illuminate\Support\Facades\Validator;

class SaveCompany
{
    public function __construct(private readonly ResolveCompany $resolveCompany) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Company
    {
        $validated = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo_path' => ['nullable'],
            'tax_id' => ['nullable', 'string', 'max:50'],
        ])->validate();

        $company = $this->resolveCompany->execute();
        $company->fill(CompanyData::fromArray($validated)->toAttributes());
        $company->save();

        return $company;
    }
}
