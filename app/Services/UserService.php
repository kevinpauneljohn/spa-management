<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function create_user(array $user)
    {
        return User::create($user);
    }

    //this will return the owner model of the current logged in user
    public function get_staff_owner()
    {
        $user = auth()->user();
        if($user->hasRole('owner'))
        {
            return $user->owner;
        }
        elseif ($user->hasRole(['therapist','manager','front desk','expense manager','HR Manager','admin']))
        {
            return $user->spa->owner;
        }
    }

    public function check_user_password($password): bool
    {
        $credentials = [
            'email' => auth()->user()->email,
            'password' => $password
        ];
        return Auth::attempt($credentials);
    }
}
