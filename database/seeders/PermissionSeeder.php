<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'add owner']);
        Permission::create(['name' => 'view owner']);
        Permission::create(['name' => 'edit owner']);
        Permission::create(['name' => 'delete owner']);
    }
}
