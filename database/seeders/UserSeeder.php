<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Spa;
use App\Models\User;
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
        $spa::create([
           'owner_id' => $ownerUser->id,
           'name' => 'Thai Khun Lounge & Spa',
           'address' => 'Mabalacat city, Pampanga',
           'number_of_rooms' => 7
        ]);
    }
}
