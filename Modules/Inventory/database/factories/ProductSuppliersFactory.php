<?php

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\ProductSuppliers;

class ProductSuppliersFactory extends Factory
{
    protected $model = ProductSuppliers::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'supplier_id' => Supplier::factory(),
            "price"=> $this->faker->numberBetween(1, 1000),
        ];
    }
}
