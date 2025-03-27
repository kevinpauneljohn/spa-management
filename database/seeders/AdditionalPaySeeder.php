<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdditionalPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view additional pay'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add additional pay'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit additional pay'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete additional pay'])->syncRoles(['owner','HR Manager']);
    }
}
