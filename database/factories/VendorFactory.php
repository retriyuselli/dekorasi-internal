<?php

namespace Database\Factories;

use App\Enums\VendorCategory;
use App\Models\Company;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->company(),
            'category' => fake()->randomElement(VendorCategory::cases()),
            'contact_person' => fake()->name(),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->companyEmail(),
            'address' => fake()->address(),
        ];
    }
}
