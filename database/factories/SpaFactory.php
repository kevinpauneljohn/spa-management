<?php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => collect(Owner::all())->pluck('id')->random(),
            'name' => $this->faker->company.' Massage & SPA',
            'address' => $this->faker->address,
            'number_of_rooms' => rand(3,7)
        ];
    }
}
