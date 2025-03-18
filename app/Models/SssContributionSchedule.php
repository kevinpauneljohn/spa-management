<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SssContributionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_range_of_compensation',
        'end_range_of_compensation',
        'regular_ss_employees_compensation',
        'mandatory_provident_fund',
        'employer_regular_ss_contribution',
        'employer_mpf_contribution',
        'employees_compensation',
        'employees_regular_ss_contribution',
        'employees_mpf_contribution'
    ];
}
