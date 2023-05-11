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

    public function therapist()
    {
        return $this->belongsTo(Therapist::class, 'employee_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }
    public function spas()
    {
        return $this->belongsTo(Spa::class,'spas_id');
    }

}
