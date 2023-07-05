<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Service;
use App\Models\Spa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->firstname = 'john kevin';
        $user->middlename = 'pama';
        $user->lastname = 'paunel';
        $user->email = 'johnkevinpaunel@gmail.com';
        $user->username = 'kevinpauneljohn';
        $user->mobile_number = '09166520817';
        $user->password = bcrypt('123');
        $user->assignRole('super admin');
        $user->save();

        $owner = new User();
        $owner->firstname = 'jamaica';
        $owner->middlename = 'fernando';
        $owner->lastname = 'soto';
        $owner->email = 'sotojamaica@yahoo.com';
        $owner->username = 'jamaica';
        $owner->mobile_number = '09166520823';
        $owner->password = bcrypt('123');
        $owner->assignRole('owner');
        $owner->save();

        $ownerUser = new Owner();
        $ownerUser->user_id = $owner->id;
        $ownerUser->save();

        $spa = new Spa();
        $spa->owner_id = $ownerUser->id;
        $spa->name = 'Thai Khun Lounge & Spa';
        $spa->address = 'Mabalacat city, Pampanga';
        $spa->number_of_rooms = rand(3,7);
        $spa->save();

        Service::factory()->count(5)->state(new Sequence(
            ['name' => 'swedish'],
            ['name' => 'siatsu'],
            ['name' => 'couple deluxe'],
            ['name' => 'Thai Massage'],
            ['name' => 'Herbal balls with rice hot pad'],
        ))->create([
            'spa_id' => $spa->id
        ]);

        Spa::factory()->has(Service::factory()->state(new Sequence(
            ['name' => 'swedish'],
            ['name' => 'siatsu'],
            ['name' => 'couple deluxe'],
            ['name' => 'Thai Massage'],
            ['name' => 'Herbal balls with rice hot pad'],
        ))->count(5))->count(2)->create();

        User::factory()->count(50)->create();
    }
}
