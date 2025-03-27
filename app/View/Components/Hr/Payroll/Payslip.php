<?php

namespace App\View\Components\Hr\Payroll;

use App\Models\Employee;
use Illuminate\View\Component;

class Payslip extends Component
{
    public $employee;
    public $payroll;
    public $daysWorked;
    public $grossBasicPay;
    public $attendance;
    public $deductions;
    public $additionalPays;
    public $netPay;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($employee = null, $payroll = null, $daysWorked = null, $grossBasicPay = null, $attendance = null, $deductions = null, $additionalPays = null, $netPay = null)
    {
        $this->employee = $employee;
        $this->payroll = $payroll;
        $this->daysWorked = $daysWorked;
        $this->grossBasicPay = $grossBasicPay;
        $this->attendance = $attendance;
        $this->deductions = $deductions;
        $this->additionalPays = $additionalPays;
        $this->netPay = $netPay;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hr.payroll.payslip');
    }
}
