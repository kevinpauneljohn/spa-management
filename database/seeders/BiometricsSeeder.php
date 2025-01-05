<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BiometricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'view biometrics'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'add biometrics'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'edit biometrics'])->syncRoles(['owner','HR Manager']);
        Permission::create(['name' => 'delete biometrics'])->syncRoles(['owner','HR Manager']);
    }
}
