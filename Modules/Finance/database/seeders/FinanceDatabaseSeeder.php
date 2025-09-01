<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\InvoiceItems;

class FinanceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory()->count(5)->create();
        InvoiceItems::factory()->count(5)->create();
        // $this->call([]);
    }
}
