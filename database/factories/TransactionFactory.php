<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Sale;
use App\Models\Spa;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public $spa;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->spa = Spa::where('name','Thai Khun Lounge & Spa')->first();
        $services = collect($this->spa->services)->random();
        $hour = rand(1,10);
        return [
            'spa_id' => $this->spa->id,
            'service_id' => $services->id,
            'service_name' => $services->name,
            'amount' => $services->price,
            'therapist_1' => collect($this->spa->therapists)->pluck('id')->random(),
            'therapist_2' => null,
            'client_id' => collect(Client::all())->pluck('id')->random(),
            'start_time' => now()->addHours($hour),
            'end_time' => now()->addHours($hour+1),
            'rating' => 0,
            'sales_type' => 'Walk-in',
            'sales_id' => null,
            'room_id' => rand(1,$this->spa->number_of_rooms),
            'primary' => 'yes'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Transaction $transaction){
            $sales = new Sale([
                'spa_id' => $transaction->spa_id,
                'amount_paid' => $transaction->amount,
                'payment_status' => 'paid',
                'user_id' => collect(User::all())->pluck('id')->random(),
                'payment_method' => 'cash',
                'paid_at' => now()
            ]);
            $sales->save();

            $transaction->sales_id = $sales->id;
            $transaction->save();
        });
    }
}
