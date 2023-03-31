<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Spa extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'number_of_rooms',
    ];
}
