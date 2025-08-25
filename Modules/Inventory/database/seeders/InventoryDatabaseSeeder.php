<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductSuppliers;
use Modules\Inventory\Models\PurchaseOrder;
use Modules\Inventory\Models\PurchaseOrderDetail;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::factory(5)->create();
        Category::factory(5)->create();
        Section::factory(5)->create();
        Product::factory(5)->create();
        ProductSuppliers::factory(5)->create();
        PurchaseOrder::factory(5)->create();
        PurchaseOrderDetail::factory(5)->create();
        // $this->call([]);
    }
}
