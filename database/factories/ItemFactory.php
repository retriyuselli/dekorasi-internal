<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'code' => str(fake()->unique()->bothify('BRG-###'))->upper()->toString(),
            'name' => fake()->words(2, true),
            'notes' => null,
            'is_active' => true,
        ];
    }
}
