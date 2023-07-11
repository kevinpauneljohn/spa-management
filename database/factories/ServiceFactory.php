<?php

namespace Database\Factories;

use App\Models\Spa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'spa_id' => collect(Spa::all())->pluck('id')->random(),
            'description' => 'lorem ipsum',
            'duration' => 6,
            'price' => 670,
            'category' => 'regular',
            'price_per_plus_time' => 100,
        ];
    }
}
