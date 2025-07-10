<?php

namespace Modules\Support\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Support\Models\Support::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

