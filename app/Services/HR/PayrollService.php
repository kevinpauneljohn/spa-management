<?php

namespace App\Services\HR;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class PayrollService extends EmployeeService
{
    public $attendance;


    public function attendance($userid)
    {
        return Attendance::where('userid',$userid);
    }

    public function get_total_daily_basic_pay($userid, $startDate, $endDate)
    {
        return $this->attendance($userid)->whereBetween('time_in',[$startDate, $endDate])->sum('daily_basic_pay');
    }

    public function get_total_late_deductions($userid, $startDate, $endDate)
    {
        return $this->attendance($userid)->whereBetween('time_in',[$startDate, $endDate])->sum('late_deductions');
    }

    public function get_total_overtime_pay($userid, $startDate, $endDate)
    {
        return $this->attendance($userid)->whereBetween('time_in',[$startDate, $endDate])->sum('overtime_pay');
    }

    public function get_payroll_net_pay($userid, $startDate, $endDate)
    {
        return ($this->get_total_daily_basic_pay($userid, $startDate, $endDate) + $this->get_total_overtime_pay($userid, $startDate, $endDate)) - $this->get_total_late_deductions($userid, $startDate, $endDate);
    }

    public function get_number_of_days_worked($userid, $startDate, $endDate)
    {
        return $this->attendance($userid)->whereBetween('time_in',[$startDate, $endDate])->count();
    }

    public function daily_basic_pay_times_number_of_worked_days($userid, $startDate, $endDate)
    {
        $daily_basic_pay = $this->attendance($userid)->first()->daily_basic_pay;
        $worked_days = $this->get_number_of_days_worked($userid, $startDate, $endDate);

        return $daily_basic_pay * $worked_days;
    }

    public function get_employees_payroll($owner_id)
    {
        $employee_ids = collect($this->get_employees_id_by_owner_id($owner_id))->toArray();
        $payrolls = $this->get_payroll_by_employee_ids($employee_ids);
        return DataTables::of($payrolls)
            ->editColumn('date_start', function($payroll){
                return '<span class="text-blue text-bold">'.$payroll->date_start.'</span>';
            })
            ->editColumn('date_end', function($payroll){
                return '<span class="text-green text-bold">'.$payroll->date_start.'</span>';
            })
            ->addColumn('employee_id', function($payroll){
                return Carbon::parse($payroll->date_start)->format('Y-m-d');
            })
            ->addColumn('employee_id', function($payroll){
                return Carbon::parse($payroll->date_end)->format('Y-m-d');
            })
            ->addColumn('employee_id', function($payroll){
                return $payroll->employee_id;
            })
            ->addColumn('name', function($payroll){
                return '<span class="text-primary">'.ucwords($payroll->employee->user->fullname).'</span>';
            })
            ->addColumn('role', function($payroll){
                $roles = '';
                foreach($payroll->employee->user->getRoleNames() as $role)
                {
                    $roles .= '<span class="badge badge-primary m-1">'.$role.'</span>';
                }
                return $roles;
            })
            ->addColumn('branch', function($payroll){
                return $payroll->employee->user->spa->name;
            })
            ->addColumn('biometric_user', function($payroll){
                return !is_null($payroll->employee->biometric) ? $payroll->employee->biometric->userid : '';
            })
            ->addColumn('action', function($payroll){
                $action = '';

                if(auth()->user()->can('view payroll'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-success mr-1 mb-1 view-payroll" id="'.$payroll->employee->id.'" data-toggle="modal" data-target="#view-payroll">View</button>';
                    $action .= '<a href="'.route('payslips.show',['payslip' => $payroll->id]).'" class="btn btn-sm btn-info mr-1 mb-1 manage-payslip" id="'.$payroll->employee->id.'">Manage Payslip</a>';

                }
                return $action;
            })
            ->rawColumns(['action','role','name','date_start','date_end'])
            ->make(true);
    }

    public function get_employees_id_by_owner_id($owner_id)
    {
        return Employee::where('owner_id', $owner_id)->get();
    }

    public function is_payroll_exists($employee_id, $date_start, $date_end): bool
    {
        return Payroll::where('employee_id', $employee_id)
            ->where('date_start', $date_start)
            ->where('date_end', $date_end)
            ->count() > 0;
    }

    public function save_payroll(array $employees, $date_start, $date_end)
    {
        foreach($employees as $employee)
        {
            if(!$this->is_payroll_exists($employee['id'], $date_start, $date_end))
            {
                Payroll::create([
                    'employee_id' => $employee['id'],
                    'date_start' => $date_start,
                    'date_end' => $date_end
                ]);
            }
        }
        return true;
    }

    public function get_payroll_by_employee_ids(array $employee_ids)
    {
        return Payroll::whereIn('employee_id', [1])->get();
    }


}
