<?php

namespace App\Services\HR;

use App\Models\Benefit;

class BenefitService
{
    public function saveEmployeeBenefits(array $employeeBenefit)
    {
        $benefits =  Benefit::updateOrCreate(
            ['employee_id' => $employeeBenefit['employee_id'],'owner_id' => $employeeBenefit['owner_id']],
            [
                'daily_basic_pay' => $employeeBenefit['daily_basic_pay'],
                'with_sss' => collect($employeeBenefit)->has('with_sss'),
                'with_pag_ibig' => collect($employeeBenefit)->has('with_pag_ibig'),
                'with_philhealth' => collect($employeeBenefit)->has('with_philhealth'),
                'with_thirteenth_month_pay' => collect($employeeBenefit)->has('with_thirteenth_month_pay'),
                'with_overtime_pay' => collect($employeeBenefit)->has('with_overtime_pay'),
                'with_holiday_pay' => collect($employeeBenefit)->has('with_holiday_pay'),
                'with_service_incentive_leaves' => collect($employeeBenefit)->has('with_service_incentive_leaves'),
                'with_maternity_leave' => collect($employeeBenefit)->has('with_maternity_leave'),
                'with_paternity_leave' => collect($employeeBenefit)->has('with_paternity_leave'),
                'sss_monthly_contribution_basis' => $employeeBenefit['social_security_system'],
                'pag_ibig_monthly_contribution_basis' => $employeeBenefit['pagibig'],
                'philhealth_monthly_contribution_basis' => $employeeBenefit['philhealth'],
            ]
        );

        if($benefits)
        {
            return ['success' => true, 'message' => 'Benefits Successfully Saved!', 'benefits' => $benefits];
        }
        return ['success' => false, 'message' => 'Something Went Wrong!'];
    }
}
