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
        $price = rand(1000,1500);
        return [
            'spa_id' => collect(Spa::all())->pluck('id')->random(),
            'description' => 'lorem ipsum',
            'duration' => 5,
            'multiple_masseur' => collect([true, false])->random(),
            'price' => $price,
            'commission_reference_amount' => $price-500,
            'category' => 'regular',
            'price_per_plus_time' => 100,
        ];
    }
}
