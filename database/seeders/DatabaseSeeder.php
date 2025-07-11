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
//                SpaSeeder::class,
                UnitOfMeasurementSeeder::class,
                PermissionSeeder::class,
                PermissionSpaSeeder::class,
                PermissionTherapistSeeder::class,
                InventoryCategorySeeder::class,
                InventorySeeder::class,
                AccessPosSeeder::class,
                ExpenseSeeder::class,
//                ClientSeeder::class,
                ActivityLogsSeeder::class,
                AppointmentSeeder::class,
                PayrollSeeder::class,
                DepartmentSeeder::class,
                ScheduleSeeder::class,
                EmployeeSeeder::class,
                ScheduleSettingSeeder::class,
                BenefitSeeder::class,
                BiometricsSeeder::class,
                AttendanceSeeder::class,
                DeductionSeeder::class,
                AdditionalPaySeeder::class,
            ]);
    }
}
