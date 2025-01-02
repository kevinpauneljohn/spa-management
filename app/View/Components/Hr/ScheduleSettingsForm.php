<?php

namespace App\View\Components\Hr;

use App\Models\Employee;
use App\Models\Schedule;
use App\Services\HR\ScheduleSettingService;
use App\Services\UserService;
use Illuminate\View\Component;

class ScheduleSettingsForm extends Component
{
    public $days_of_work;
    public $schedules;
    public $employee;
    public $is_employee_have_saved_schedule;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, ScheduleSettingService $scheduleSettingService,$employee)
    {
        $this->employee = $employee;
        $this->is_employee_have_saved_schedule = $scheduleSettingService->is_employee_have_saved_schedule($this->employee->id);
        $this->days_of_work = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $this->schedules = Schedule::where('owner_id',$userService->get_staff_owner()->id)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hr.schedule-settings-form');
    }
}
