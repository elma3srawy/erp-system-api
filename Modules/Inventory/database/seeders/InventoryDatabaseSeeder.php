<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductSuppliers;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::factory(50)->create();
        Category::factory(50)->create();
        Section::factory(50)->create();
        Product::factory(50)->create();
        ProductSuppliers::factory(50)->create();
        // $this->call([]);
    }
}
