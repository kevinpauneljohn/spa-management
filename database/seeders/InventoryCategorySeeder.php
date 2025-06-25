<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        $permissions = ['name' => 'view category','add category','edit category','delete category'];
        foreach ($permissions as $permission)
        {
            if(\App\Models\Permission::where('name',$permission)->count() == 0)
            {
                \Spatie\Permission\Models\Permission::create(['name' => $permission])->syncRoles(['owner','manager']);
            }
        }

//        InventoryCategory::factory()->count(3)->state(new Sequence(
//            ['name' => 'consumable'],
//            ['name' => 'washable'],
//            ['name' => 'refillable'],
//        ))->create();
    }
}
