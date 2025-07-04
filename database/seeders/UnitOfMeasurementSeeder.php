<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasurement;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitOfMeasurement::insert([
            ['singular' => 'pack', 'plural' => 'packs', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'meter', 'plural' => 'meters', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'centimeter', 'plural' => 'centimeters', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'inch', 'plural' => 'inches', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'millimeter', 'plural' => 'millimeters', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'foot', 'plural' => 'feet', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'liter', 'plural' => 'liters', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'gallon', 'plural' => 'gallons', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'piece', 'plural' => 'pieces', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'ounce', 'plural' => 'ounces', 'created_at' => now(), 'updated_at' => now()],
//            ['singular' => 'bottle', 'plural' => 'bottles', 'created_at' => now(), 'updated_at' => now()],
        ]);

//        Permission::create(['name' => 'add measurement'])->assignRole('owner');
//        Permission::create(['name' => 'view measurement'])->assignRole('owner');
//        Permission::create(['name' => 'edit measurement'])->assignRole('owner');
//        Permission::create(['name' => 'delete measurement'])->assignRole('owner');
    }
}
