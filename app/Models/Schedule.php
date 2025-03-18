<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    ///instead of Shift we will use the Schedule model as alternative to shift
    /// because the previous intern made a bad code and used the Shift
    /// i dont want to delete their code because it may cause problem
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','time_in','time_out','break_in','break_out','owner_id','user_id'];

    public function scheduleSettings()
    {
        return $this->hasMany(ScheduleSetting::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
