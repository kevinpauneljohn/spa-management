<?php

namespace App\Services;

use App\Models\User;

class RoleServices
{
    public function getRole()
    {
        $invalidRoles = ['admin', 'super admin', 'owner', 'therapist'];
        $checkRoles = User::whereDoesntHave('roles', function ($query) use ($invalidRoles){
            $query->whereIn('name', $invalidRoles);
        })->get();
        return $checkRoles;
    }
}
