<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create_user(array $user)
    {
        return User::create($user);
    }
}
