<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Expense extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['title','description','amount','spa_id','date_expended'];

    protected static $logAttributes = ['title','description','amount','spa_id','date_expended'];
}

