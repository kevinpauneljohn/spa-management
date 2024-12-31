<?php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => '25939d17-2e70-4c05-a066-653c51e7f0bf',
            'name' => $this->faker->company(),
            'user_id' => '7174c34d-bcef-4da1-b44a-309780af9101',
        ];
    }
}
