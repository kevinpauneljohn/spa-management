<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(DiscountService $discountService)
    {
       Discount::factory()
           ->count(2)
           ->state(new Sequence(
               ['is_amount' => true],
               ['is_amount' => false]
           ))
           ->state(new Sequence(
               ['amount' => rand(580,1000)],
               ['amount' => 0],
           ))
           ->state(new Sequence(
               ['percent' => 0],
               ['percent' => rand(10,90)],
           ))
           ->create();
    }
}
