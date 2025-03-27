<?php

namespace App\Services\HR;

use App\Models\Employee;
use App\Models\Payroll;
use App\View\Components\Hr\Payroll\Payslip;

class PayslipService extends DeductionService
{
    public function getPayslip()
    {

    }

    public function get_owner_id_by_payroll_id($payroll_id)
    {
        $payroll = Payroll::findOrFail($payroll_id);
        $employee = Employee::findOrFail($payroll->employee_id);
        return $employee->user->owner->id;
    }

}
