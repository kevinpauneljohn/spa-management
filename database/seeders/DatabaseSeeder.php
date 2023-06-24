<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        $this->call([RoleSeeder::class, UserSeeder::class]);
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UnitOfMeasurementSeeder::class,
            AccessPosSeeder::class,
//            PermissionSeeder::class,
//            PermissionSpaSeeder::class,
//            PermissionTherapistSeeder::class,
            InventorySeeder::class,
            InventoryCategorySeeder::class
            ]);
    }
}
