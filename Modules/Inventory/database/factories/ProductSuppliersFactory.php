<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\ProductSuppliers;

class ProductSuppliersFactory extends Factory
{
    protected $model = ProductSuppliers::class;

    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 50),
            'supplier_id' => $this->faker->numberBetween(1, 50),
            "price"=> $this->faker->numberBetween(1, 1000),
        ];
    }
}
