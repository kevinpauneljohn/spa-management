<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UsesUuid;

class Client extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'lastname',
        'date_of_birth',
        'mobile_number',
        'email',
        'address',
        'client_type'
    ];

    protected $appends = ['full_name'];

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function owners(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Owner::class);
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function discounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
