<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ScheduleSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view schedule settings'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add schedule settings'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit schedule settings'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete schedule settings'])->syncRoles(['owner','HR Manager']);
    }
}
