<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'add spa']);
        Permission::create(['name' => 'view spa']);
        Permission::create(['name' => 'edit spa']);
        Permission::create(['name' => 'delete spa']);
    }
}
