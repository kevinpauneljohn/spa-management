<?php

namespace App\Services;

use App\Models\Owner;
use App\Models\Spa;
use App\Models\User;

class OwnerServices
{
    public $spa;
    public $owner;

    public function getOwnerBySpaID($spaId)
    {
        $spa = Spa::findOrFail($spaId);
        return $spa->owner;
    }

    public function create_owner_as_user(array $ownerData)
    {
        $user = User::create([
            'firstname' => $ownerData['firstname'],
            'middlename' => $ownerData['middlename'],
            'lastname' => $ownerData['lastname'],
            'email' => $ownerData['email'],
            'username' => $ownerData['username'],
            'password' => bcrypt($ownerData['password']),
        ]);
        $user->assignRole('owner');

        return $user;
    }

    public function link_user_as_owner($user_id): bool
    {
        $owner = new Owner();
        $owner->user_id = $user_id;
        return $owner->save();
    }
}
