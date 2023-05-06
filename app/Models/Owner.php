<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

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

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function categories()
    {
        return $this->hasMany(InventoryCategory::class);
    }
}
