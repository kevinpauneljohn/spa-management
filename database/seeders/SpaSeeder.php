<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Spa;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Spa::factory()->count(3)->create();
        Spa::factory()->has(Service::factory()->state(new Sequence(
            ['name' => 'swedish'],
            ['name' => 'siatsu'],
            ['name' => 'couple deluxe'],
            ['name' => 'Thai Massage'],
            ['name' => 'Herbal balls with rice hot pad'],
        ))->count(5))->count(2)->create();
    }
}
