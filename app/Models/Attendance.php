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

}
