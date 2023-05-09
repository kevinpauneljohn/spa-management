<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'add category'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'view category'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'edit category'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'delete category'])->assignRole(['owner','manager']);
    }
}
