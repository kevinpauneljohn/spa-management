<?php

namespace Database\Seeders;

use App\Models\Inventory;
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
        $permissions = ['name' => 'view inventory','add inventory','edit inventory','delete inventory','manage inventory'];
        foreach ($permissions as $permission)
        {
            if(\App\Models\Permission::where('name',$permission)->count() == 0)
            {
                \Spatie\Permission\Models\Permission::create(['name' => $permission])->syncRoles(['owner','manager','inventory manager','front desk']);
            }
        }

        Inventory::factory()->count(15)->create();
    }
}
