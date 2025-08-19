<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Section;

class SectionFactory extends Factory
{
    protected $model = Section::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'category_id' => $this->faker->numberBetween(1, 20)
        ];
    }
}
