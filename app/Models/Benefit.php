<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id','employee_id',
        'daily_basic_pay','with_sss','with_pag_ibig','with_philhealth',
        'with_thirteenth_month_pay','with_overtime_pay','with_holiday_pay',
        'with_service_incentive_leaves','with_maternity_leave','with_paternity_leave',
        'sss_monthly_contribution_basis','pag_ibig_monthly_contribution_basis','philhealth_monthly_contribution_basis'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
