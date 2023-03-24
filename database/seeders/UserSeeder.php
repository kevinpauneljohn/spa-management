<?php

namespace Database\Seeders;

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
    }
}
