<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Owner extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'user_id'
    ];

    public function spas()
    {
        return $this->hasMany(Spa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //get all owners therapist
    public function therapists()
    {
        return $this->hasOneThrough(Therapist::class, Spa::class);
    }
}
