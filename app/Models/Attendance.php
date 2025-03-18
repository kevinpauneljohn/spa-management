<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['time_in','time_out','break_in','break_out','is_overtime_allowed'];
    protected $table = 'attendances';

    protected $attributes = [
        'is_overtime_allowed' => false
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    private function getEmployeeId()
    {
        return Biometric::where('userid',$this->userid)->firstOrFail()->employee_id;
    }

    public function getEmployeeName()
    {
        return Employee::findOrFail($this->getEmployeeId())->user->fullname;
    }

}
