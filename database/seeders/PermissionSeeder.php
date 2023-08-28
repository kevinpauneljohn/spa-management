<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'download attendance'])->syncRoles(['owner']);
        Permission::create(['name' => 'process payment'])->syncRoles(['owner','manager','front desk']);
        Permission::create(['name' => 'isolate transaction'])->syncRoles(['owner','manager','front desk']);
        Permission::create(['name' => 'view sales management'])->syncRoles(['owner']);

        Permission::create(['name' => 'view product'])->syncRoles(['owner']);
        Permission::create(['name' => 'add product'])->syncRoles(['owner']);
        Permission::create(['name' => 'edit product'])->syncRoles(['owner']);
        Permission::create(['name' => 'delete product'])->syncRoles(['owner']);
    }
}
