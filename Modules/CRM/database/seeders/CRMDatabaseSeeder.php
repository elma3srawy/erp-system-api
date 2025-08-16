<?php

namespace Modules\CRM\Database\Seeders;
use Modules\CRM\Models\Customer;
use Illuminate\Database\Seeder;

class CRMDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(150)->create();
        // $this->call([]);
    }
}
