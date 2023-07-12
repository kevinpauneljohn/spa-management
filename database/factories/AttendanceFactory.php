<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'break_in' => '-',
            'break_out' => '-',
            'allow_OT' => 0,
            'OT' => 0,
        ];
    }
}
