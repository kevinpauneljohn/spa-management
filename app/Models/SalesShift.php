<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_shift',
        'end_shift',
        'user_id',
        'spa_id',
        'start_money',
        'confirm_start_shift',
        'confirm_end_shift',
        'confirm_start_money'
    ];

    protected static $logAttributes = [
        'start_shift',
        'end_shift',
        'user_id',
        'spa_id',
        'start_money',
        'confirm_start_shift',
        'confirm_end_shift',
        'confirm_start_money'
    ];
}
