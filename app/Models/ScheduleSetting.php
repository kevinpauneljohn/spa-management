<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSetting extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id','days_of_work','schedule_id','employee_id'];

    protected $casts = [
        'days_of_work' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
