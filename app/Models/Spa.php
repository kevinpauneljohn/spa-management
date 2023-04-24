<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spa extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'number_of_rooms',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function therapists()
    {
        return $this->hasMany(Therapist::class);
    }


}
