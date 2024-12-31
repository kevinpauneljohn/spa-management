<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view schedule'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add schedule'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit schedule'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete schedule'])->syncRoles(['owner','HR Manager']);
    }
}
