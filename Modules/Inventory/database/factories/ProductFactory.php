<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'sku' => $this->faker->unique()->word,
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(1, 1000),
            'section_id' => Section::factory(),
        ];
    }
}
