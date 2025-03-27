<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','date_start','date_end'];
    protected $table = 'payroll';
//    public function employee()
//    {
//        return $this->belongsTo(EmployeeTable::class, 'employee_id');
//    }

    public function deductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Deduction::class);
    }

    public function additionalPays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdditionalPay::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
