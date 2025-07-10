<?php

namespace Modules\HR\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\HR\Models\Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

