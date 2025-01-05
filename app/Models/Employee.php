<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule_setting()
    {
        return $this->hasOne(ScheduleSetting::class);
    }

    public function biometric()
    {
        return $this->hasOne(Biometric::class);
    }
}
