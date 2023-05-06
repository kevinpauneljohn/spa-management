<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'add inventory'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'view inventory'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'edit inventory'])->assignRole(['owner','manager']);
        Permission::create(['name' => 'delete inventory'])->assignRole(['owner','manager']);
    }
}
