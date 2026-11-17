<?php

namespace App\Modules\MasterData\Actions;

use App\Enums\ProspectStatus;
use App\Models\Customer;
use App\Models\Prospect;
use Illuminate\Support\Facades\DB;

class ConvertProspectToCustomer
{
    public function execute(Prospect $prospect): Customer
    {
        return DB::transaction(function () use ($prospect): Customer {
            $locked = Prospect::query()->whereKey($prospect->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->customer_id) {
                return Customer::query()->findOrFail($locked->customer_id);
            }

            $customer = Customer::query()->create([
                'company_id' => $locked->company_id,
                'name' => $locked->name,
                'type' => $locked->type,
                'phone' => $locked->phone,
                'email' => $locked->email,
                'address' => $locked->address,
                'notes' => $locked->notes,
            ]);

            $locked->forceFill([
                'customer_id' => $customer->id,
                'status' => ProspectStatus::Converted,
            ])->save();

            return $customer;
        });
    }
}
