<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view benefit'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add benefit'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit benefit'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete benefit'])->syncRoles(['owner','HR Manager']);
    }
}
