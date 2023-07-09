<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\Spa;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $spa = Spa::where("name","=","Thai Khun Lounge & Spa")->first();
        return [
            'spa_id' => $spa->id,
            'owner_id' => $spa->owner->id,
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(3),
            'quantity' => rand(20,200),
            'restock_limit' => rand(5,10),
            'unit' => collect(UnitOfMeasurement::all())->pluck('singular')->random(),
            'category' => collect(InventoryCategory::all())->pluck('id')->random()
        ];
    }
}
