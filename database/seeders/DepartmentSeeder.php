<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'HR Manager']);
        Permission::create(['name' => 'view department'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add department'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit department'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete department'])->syncRoles(['owner','HR Manager']);
//        Department::factory()->count(5)->create();
    }
}
