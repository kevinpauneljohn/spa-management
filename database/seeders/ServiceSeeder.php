<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::factory()->count(10)->state(new Sequence(
            ['name' => 'swedish'],
            ['name' => 'siatsu'],
            ['name' => 'couple deluxe'],
            ['name' => 'Thai Massage'],
            ['name' => 'Herbal balls with rice hot pad'],
        ))->create();
    }
}
