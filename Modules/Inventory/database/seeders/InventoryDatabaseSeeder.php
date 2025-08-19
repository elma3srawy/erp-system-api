<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Supplier;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::factory(150)->create();
        Category::factory(20)->create();
        Section::factory(100)->create();
        // $this->call([]);
    }
}
