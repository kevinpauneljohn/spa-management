<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['name' => 'view expenses','add expenses','edit expenses','delete expenses'];
        foreach ($permissions as $permission)
        {
            if(Permission::where('name',$permission)->count() == 0)
            {
                \Spatie\Permission\Models\Permission::create(['name' => $permission])->syncRoles(['owner','expense manager']);
            }
        }

//        Expense::factory()->count(20)->create();

    }
}
