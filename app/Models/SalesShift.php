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
        'completed'
    ];

    protected static $logAttributes = [
        'start_shift',
        'end_shift',
        'user_id',
        'spa_id',
        'start_money',
    ];
}
