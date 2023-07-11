<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->name,
            'middlename' => $this->faker->name,
            'lastname' => $this->faker->name,
            'mobile_number' => rand(1111111111,9999999999),
            'email' => $this->faker->unique()->email,
            'address' => $this->faker->address,
            'client_type' => 'new'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Client $client){
            DB::table('client_owner')->insert([
                'client_id' => $client->id,
                'owner_id' => collect(Owner::all())->pluck('id')->random()
            ]);
        });
    }
}
