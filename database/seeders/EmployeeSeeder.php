<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view employee'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add employee'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit employee'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete employee'])->syncRoles(['owner','HR Manager']);
    }
}
