<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Department;
use Modules\Core\Models\Admin;
use Modules\Core\Models\User;
class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);
        Department::factory()->count(150)->create();
        Admin::factory()->count(150)->create();
        User::factory()->count(150)->create();
    }
}
