<?php

namespace Modules\Finance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Finance\Models\Invoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => \Modules\Sales\Models\Order::factory(),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid', 'overdue']),
            'subtotal' => $this->faker->randomFloat(2, 100, 1000),
            'paid_amount' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}

