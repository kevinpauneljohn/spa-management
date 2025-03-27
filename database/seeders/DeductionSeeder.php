<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view deduction'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add deduction'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit deduction'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete deduction'])->syncRoles(['owner','HR Manager']);
    }
}
