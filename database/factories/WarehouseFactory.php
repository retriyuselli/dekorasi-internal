<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => 'Gudang '.fake()->city(),
            'code' => fake()->unique()->bothify('GD-###'),
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
