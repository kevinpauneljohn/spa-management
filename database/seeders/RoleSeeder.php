<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::create(['name' => 'super admin']);
        $admin = Role::create(['name' => 'admin']);
        $owner = Role::create(['name' => 'owner']);
        $receptionist = Role::create(['name' => 'front desk']);
        $manager = Role::create(['name' => 'manager']);
        $manager = Role::create(['name' => 'therapist']);

        Permission::create(['name' => 'add owner']);
        Permission::create(['name' => 'view owner']);
        Permission::create(['name' => 'edit owner']);
        Permission::create(['name' => 'delete owner']);

        Permission::create(['name' => 'add spa'])->assignRole($owner);
        Permission::create(['name' => 'view spa'])->assignRole($owner);
        Permission::create(['name' => 'edit spa'])->assignRole($owner);
        Permission::create(['name' => 'delete spa'])->assignRole($owner);

        Permission::create(['name' => 'add therapist'])->assignRole($owner);
        Permission::create(['name' => 'view therapist'])->assignRole($owner);
        Permission::create(['name' => 'edit therapist'])->assignRole($owner);
        Permission::create(['name' => 'delete therapist'])->assignRole($owner);

        Permission::create(['name' => 'add service'])->assignRole($owner);
        Permission::create(['name' => 'view service'])->assignRole($owner);
        Permission::create(['name' => 'edit service'])->assignRole($owner);
        Permission::create(['name' => 'delete service'])->assignRole($owner);

        Permission::create(['name' => 'add sales'])->assignRole($receptionist);
        Permission::create(['name' => 'view sales'])->assignRole($receptionist);
        Permission::create(['name' => 'edit sales'])->assignRole($receptionist);
        Permission::create(['name' => 'delete sales'])->assignRole($receptionist);
        Permission::create(['name' => 'view invoices'])->assignRole($receptionist);
        Permission::create(['name' => 'move sales'])->assignRole($receptionist);

        Permission::create(['name' => 'add staff'])->assignRole($owner);
        Permission::create(['name' => 'view staff'])->assignRole($owner);
        Permission::create(['name' => 'edit staff'])->assignRole($owner);
        Permission::create(['name' => 'delete staff'])->assignRole($owner);
        Permission::create(['name' => 'change staff password'])->assignRole($owner);

        Permission::create(['name' => 'view client'])->assignRole($owner, $receptionist, $manager);
        Permission::create(['name' => 'edit client'])->assignRole($owner, $receptionist, $manager);
        Permission::create(['name' => 'delete client'])->assignRole([$owner]);

    }
}
