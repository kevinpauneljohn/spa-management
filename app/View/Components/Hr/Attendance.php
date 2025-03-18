<?php

namespace App\View\Components\Hr;

use App\Services\HR\EmployeeService;
use Illuminate\View\Component;

class Attendance extends Component
{
    public $employee;
    public $employees;
    public $ownerId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(EmployeeService $employeeService, $ownerId, $employee = null)
    {
        $this->ownerId = $ownerId;
        $this->employee = $employee;
        $this->employees = $employeeService->getEmployeesByOwnerId($ownerId);
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hr.attendance');
    }
}
