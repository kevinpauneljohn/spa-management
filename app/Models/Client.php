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

    public function transaction()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
