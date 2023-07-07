<?php

namespace Database\Factories;

use App\Models\Spa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = now()->addDay(rand(1,50));
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->text,
            'amount' => rand(100,2000),
            'spa_id' => Spa::where('name','Thai Khun Lounge & Spa')->first()->id,
            'created_at' =>  $date,
            'updated_at' => $date
        ];
    }
}
