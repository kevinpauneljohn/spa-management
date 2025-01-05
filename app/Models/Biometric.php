<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    use HasFactory;

    protected $fillable = ['userid','employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
