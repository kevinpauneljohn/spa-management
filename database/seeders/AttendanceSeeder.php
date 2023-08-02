<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendance::factory()->count(5)->state(new Sequence(
            ['time_in' => Carbon::parse(\date('2023-07-10 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_in' => Carbon::parse(\date('2023-07-11 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_in' => Carbon::parse(\date('2023-07-12 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_in' => Carbon::parse(\date('2023-07-13 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_in' => Carbon::parse(\date('2023-07-14 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
        ))->state(new Sequence(
            ['time_out' => Carbon::parse(\date('2023-07-11 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_out' => Carbon::parse(\date('2023-07-12 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_out' => Carbon::parse(\date('2023-07-13 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_out' => Carbon::parse(\date('2023-07-14 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
            ['time_out' => Carbon::parse(\date('2023-07-15 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s')],
        ))->create([
            'employee_id' => 4
        ]);
    }
}
