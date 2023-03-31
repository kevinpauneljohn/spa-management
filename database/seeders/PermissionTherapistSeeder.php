<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTherapistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'add therapist']);
        Permission::create(['name' => 'view therapist']);
        Permission::create(['name' => 'edit therapist']);
        Permission::create(['name' => 'delete therapist']);
    }
}
