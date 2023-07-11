<?php

namespace Database\Factories;

use App\Models\Spa;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->paragraph(3),
            'owner_id' => Spa::where('name',"Thai Khun Lounge & Spa")->first()->owner->id,
        ];
    }
}
