<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
class Service extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'spa_id',
        'name',
        'description',
        'duration',
        'price',
        'category',
    ];
}
