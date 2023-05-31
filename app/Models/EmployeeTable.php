<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Therapist;
use App\Models\Spa;

class EmployeeTable extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $with = ['user'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function spas()
    {
        return $this->belongsTo(Spa::class, 'spa_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shift()
    {
        return $this->hasMany(Shift::class);
    }
}
