<?php

namespace Database\Factories;

use App\Enums\CustomerType;
use App\Enums\ProspectStatus;
use App\Models\Company;
use App\Models\Prospect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prospect>
 */
class ProspectFactory extends Factory
{
    protected $model = Prospect::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->name(),
            'type' => fake()->randomElement(CustomerType::cases()),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->safeEmail(),
            'address' => fake()->city(),
            'event_interest' => 'Pernikahan',
            'status' => ProspectStatus::New,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
