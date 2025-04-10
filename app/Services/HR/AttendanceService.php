<?php

namespace App\Services\HR;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Schedule;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class AttendanceService extends ScheduleService
{
    public $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function saveAttendance($punch_type, $attendance, $biometrics_userid): array
    {
        if($punch_type == 10)
        {
            if($this->timeIn($attendance, $biometrics_userid))
            {
                return ['success' => true, 'message' => 'Time in successfully'];
            }
            return ['success' => false, 'message' => 'Time in was not successful'];
        }
        elseif($punch_type == 11)
        {
            if($this->timeOut($attendance, $biometrics_userid))
            {
                return ['success' => true, 'message' => 'Time out successfully'];
            }
            return ['success' => false, 'message' => 'Time out was not successful'];
        }
        return [];
    }

    public function updateAttendance($attendance_id, array $data): array
    {
        $attendance = Attendance::findOrFail($attendance_id);

        $schedule_id = $this->get_schedule_id($attendance->userid);
        $schedule = $this->get_schedule_by_id($schedule_id);
        $scheduled_time_out = Carbon::parse($attendance->time_out)->format('Y-m-d').' '.$attendance->schedule->time_out;

        $attendance->time_in = !empty($data['time_in']) ? Carbon::parse($data['time_in'])->format('Y-m-d H:i:s') : null;
        $attendance->time_out = !empty($data['time_out']) ? Carbon::parse($data['time_out'])->format('Y-m-d H:i:s') : null;
        $attendance->break_in = !empty($data['break_in']) ? Carbon::parse($data['break_in'])->format('Y-m-d H:i:s') : null;
        $attendance->break_out = !empty($data['break_out']) ? Carbon::parse($data['break_out'])->format('Y-m-d H:i:s') : null;
        $attendance->is_overtime_allowed = collect($data)->has('is_overtime_allowed');
        $attendance->overtime_taken_in_hours = $attendance->is_overtime_allowed ?
            $this->get_total_overtime($attendance->time_out, $scheduled_time_out) : 0;
        $attendance->total_late_hours = $this->get_hours_late($attendance->time_in, $attendance->schedule_id);
        $attendance->daily_basic_pay = $this->get_employee_daily_basic_pay($this->get_employee_id($attendance->userid));
        $attendance->late_deductions = $this->late_amount_deductions(
            $attendance->userid,
            $data['time_in'],
            $attendance->schedule_id
        );
        $attendance->overtime_pay = $this->get_overtime_pay_amount($attendance->time_out, $schedule->time_out, $schedule_id, $attendance->daily_basic_pay, $attendance->is_overtime_allowed);
        if($attendance->isDirty())
        {
            $attendance->user_id = auth()->user()->id;
            if($attendance->save())
            {
                return ['success' => true, 'message' => 'Attendance successfully updated!'];
            }
            return ['success' => false, 'message' => 'Attendance not updated!'];
        }
        return ['success' => false, 'message' => 'No changes made!'];
    }

    private function timeIn($time_in, $biometrics_userid): bool
    {
        if(!$this->is_employee_have_saved_schedule($this->get_employee_id($biometrics_userid)))
        {
            return false;
        }

        $attendance = new Attendance();
        $attendance->time_in = $time_in;
        $attendance->userid = $biometrics_userid;
        $attendance->schedule_id = $this->get_schedule_id($biometrics_userid);
        $attendance->daily_basic_pay = $this->get_employee_daily_basic_pay($this->get_employee_id($biometrics_userid));
        $attendance->total_late_hours = $this->get_hours_late($attendance->time_in, $attendance->schedule_id);

        return $attendance->save();
    }

    private function timeOut($time_out, $biometrics_userid): bool
    {
        $attendance = Attendance::where('userid', $biometrics_userid)->where('time_in', '!=', null)->where('time_out',null);
        if($attendance->count() > 0)
        {
            $attendance = $attendance->first();
            $scheduled_time_out = Carbon::parse($attendance->time_out)->format('Y-m-d').' '.$attendance->schedule->time_out;

            $attendance->time_out = $time_out;
            $attendance->overtime_taken_in_hours = $attendance->is_overtime_allowed ?
                $this->get_total_overtime($attendance->time_out, $scheduled_time_out) : 0;
            $attendance->save();
            return true;
        }
        return false;
    }

    public function save_employee_attendance_manually(array $data): array
    {

        $schedule_id = $this->get_schedule_id($data['biometrics_user']);
        $schedule = $this->get_schedule_by_id($schedule_id);
        $biometric_user_id = $data['biometrics_user'];

        $attendance = new Attendance();
        $scheduled_time_out = Carbon::parse($attendance->time_out)->format('Y-m-d').' '.$attendance->schedule->time_out;

        $attendance->time_in = !empty($data['time_in']) ? Carbon::parse($data['time_in'])->format('Y-m-d H:i:s') : null;
        $attendance->time_out = !empty($data['time_out']) ? Carbon::parse($data['time_out'])->format('Y-m-d H:i:s') : null;
        $attendance->break_in = !empty($data['break_in']) ? Carbon::parse($data['break_in'])->format('Y-m-d H:i:s') : null;
        $attendance->break_out = !empty($data['break_out']) ? Carbon::parse($data['break_out'])->format('Y-m-d H:i:s') : null;
        $attendance->user_id = auth()->user()->id;
        $attendance->userid = $biometric_user_id;
        $attendance->schedule_id = $this->get_schedule_id($biometric_user_id);
        $attendance->is_overtime_allowed = collect($data)->has('is_overtime_allowed');
        $attendance->overtime_taken_in_hours = $attendance->is_overtime_allowed ?
            $this->get_total_overtime($attendance->time_out, $scheduled_time_out) : 0;
        $attendance->total_late_hours = $this->get_hours_late($attendance->time_in, $attendance->schedule_id);
        $attendance->daily_basic_pay = $this->get_employee_daily_basic_pay($this->get_employee_id($biometric_user_id));
        $attendance->late_deductions = $this->late_amount_deductions(
            $attendance->userid,
            $data['time_in'],
            $attendance->schedule_id
        );
        $attendance->overtime_pay = $this->get_overtime_pay_amount(
            $attendance->time_out, $schedule->time_out, $schedule_id, $attendance->daily_basic_pay, $attendance->is_overtime_allowed);

        if($attendance->save())
        {
            return ['success' => true, 'message' => 'Attendance successfully Added!'];
        }
        return ['success' => false, 'message' => 'An error occurred!'];
    }

    public function late_counter_in_minutes($time_in, $schedule_time_in)
    {
        $late_in_minutes = $this->getTotalMinutes($schedule_time_in, $time_in);
        return max(number_format($late_in_minutes,2), 0);
    }

    public function get_default_work_hours($schedule_id): int
    {
        $schedule = $this->get_schedule_by_id($schedule_id);
        return Carbon::parse($schedule->time_in)->diffInHours($schedule->time_out, false);
    }

    public function get_total_overtime($time_out, $schedule_time_out)
    {
        $total_overtime = Carbon::parse($schedule_time_out)->diffInHours($time_out,false);
        return max(number_format($total_overtime,2), 0);
    }

    public function get_overtime_pay_amount($employee_time_out, $schedule_time_out, $schedule_id, $daily_basic_pay, $is_overtime_allowed)
    {
        if($is_overtime_allowed)
        {
            $work_hours = $this->get_default_work_hours($schedule_id) - 1;
            $daily_basic_pay_per_hour = $daily_basic_pay / $work_hours;

            $scheduled_time_out = Carbon::parse($employee_time_out)->format('Y-m-d').' '.$schedule_time_out;
            $total_overtime_hours = $this->get_total_overtime($employee_time_out, $scheduled_time_out);

            return $total_overtime_hours * $daily_basic_pay_per_hour;
        }
        return 0;
    }

    public function get_hours_late($employee_time_in, $schedule_id)
    {
        $schedule = $this->get_schedule_by_id($schedule_id);
        $date_time_in = Carbon::parse($employee_time_in)->format('Y-m-d');

        $scheduled_time_in = Carbon::parse($date_time_in.' '.$schedule->time_in)->format('Y-m-d H:i');
        $employee_time_in = Carbon::parse($employee_time_in)->format('Y-m-d H:i');
        $late = Carbon::parse($scheduled_time_in)->diffInMinutes($employee_time_in,false) / 60;

        return $late > 0.25 && $late < 1 ? 1 : round($late);
    }

    public function late_amount_deductions($biometric_user_id, $time_in, $schedule_id)
    {
        $employee_id = $this->get_employee_id($biometric_user_id);
        $late = $this->get_hours_late($time_in, $schedule_id);

        return $this->daily_basic_pay_less_late_deductions($employee_id, $schedule_id) * $late;
    }

    public function daily_basic_pay_less_late_deductions($employee_id, $schedule_id)
    {
        $schedule = Schedule::find($schedule_id);

        $employee_daily_basic_pay = $this->get_employee_daily_basic_pay($employee_id);
        $total_work_hours = $this->getTotalHours($schedule->time_in, $schedule->time_out) - 1;

        return $employee_daily_basic_pay / $total_work_hours;
    }

    public function daily_net_pay(array $attendance)
    {
        return ($attendance['daily_basic_pay'] + $attendance['overtime_pay']) - $attendance['late_deductions'];
    }

    public function getBiometricUserId(): \Illuminate\Support\Collection
    {
        return collect(Employee::where('owner_id',$this->userService->get_staff_owner()->id)->get())->map(function($item, $key){
            $employee = Employee::find($item['id']);
            return collect($item)->merge(['biometrics_user_id' => is_null($employee->biometric) ? 0 : $employee->biometric->userid]);
        })->pluck('biometrics_user_id');
    }

    public function employeeAttendance($request, $employee_id)
    {
        $startDate = $request->session()->get('attendance_start_date');
        $endDate = $request->session()->get('attendance_end_date');

        if(is_null($employee_id))
        {
            $employees = $this->getBiometricUserId();
            $attendances = Attendance::whereIn('userid',$employees)->whereBetween('time_in',[$startDate, $endDate])->get();
        }
        else{
            $employee = Employee::find($employee_id);
            $attendances = Attendance::where('userid',$employee->biometric->userid)->whereBetween('time_in',[$startDate, $endDate])->get();
        }


        return DataTables::of($attendances)
            ->addColumn('name',function($attendance){
                return '<a href="'.route('employees.show',['employee' => $this->get_employee_id($attendance->userid)]).'">'.ucwords($attendance->getEmployeeName()).'</a>';
            })
            ->editColumn('time_in', function($attendance){
                return !is_null($attendance->time_in) ? Carbon::parse($attendance->time_in)->format('Y-m-d h:i:s a') : '';
            })
            ->editColumn('time_out', function($attendance){
                return !is_null($attendance->time_out) ? Carbon::parse($attendance->time_out)->format('Y-m-d h:i:s a') : '';
            })
            ->editColumn('break_in', function($attendance){
                return !is_null($attendance->break_in) ? Carbon::parse($attendance->break_in)->format('Y-m-d h:i:s a') : '';
            })
            ->editColumn('break_out', function($attendance){
                return !is_null($attendance->break_out) ? Carbon::parse($attendance->break_out)->format('Y-m-d h:i:s a') : '';
            })
            ->addColumn('total_hours',function($attendance){
                return $this->getTotalHours($attendance->time_in, $attendance->time_out);
            })
            ->addColumn('total_break_in_minutes', function ($attendance) {
                return $this->getTotalMinutes($attendance->break_in, $attendance->break_out);
            })
            ->addColumn('total_hours_less_break', function ($attendance) {
                return number_format($this->getTotalHoursLessBreak($attendance->time_in, $attendance->time_out, $attendance->break_in, $attendance->break_out),2);
            })
            ->addColumn('late_in_minutes', function($attendance){
                $scheduled_time_in = Carbon::parse($attendance->time_in)->format('Y-m-d').' '.$attendance->schedule->time_in;
                return $this->late_counter_in_minutes($attendance->time_in, $scheduled_time_in);
            })
            ->addColumn('total_work_hours', function($attendance){
                return $this->get_default_work_hours($attendance->schedule_id);
            })
            ->editColumn('is_overtime_allowed', function($attendance){
                return $attendance->is_overtime_allowed ? '<span class="badge badge-success">Approved</span>' : '';
            })
            ->editColumn('total_overtime', function($attendance){
                return $attendance->overtime_taken_in_hours;
            })
            ->editColumn('updated_at', function($attendance){
                return $attendance->updated_at->format('Y-m-d h:i:s a');
            })
            ->editColumn('user_id', function($attendance){
                return !is_null($attendance->user) ? ucwords($attendance->user->fullname) : '';
            })
            ->addColumn('basic_pay', function($attendance){
                return number_format($attendance->daily_basic_pay,2);
            })
            ->addColumn('late_deductions', function($attendance){

                return $attendance->late_deductions;
            })
            ->addColumn('overtime_pay', function($attendance){
                return $attendance->overtime_pay;
            })
            ->addColumn('net_pay', function($attendance){
                return number_format($this->daily_net_pay(collect($attendance)->toArray()),2);
            })
            ->addColumn('action', function($attendance){
                $action = '';
                if(auth()->user()->can('view attendance') && Route::current()->getName() !== 'employee-attendance')
                {
                    $action .= '<a href="'.route('employees.show',['employee' => $this->get_employee_id($attendance->userid)]).'" class="btn btn-sm btn-success mr-1 mb-1 view-attendance">View</a>';
                }

                if(auth()->user()->can('edit attendance'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 mb-1 edit-attendance" id="'.$attendance->id.'" data-toggle="modal" data-target="#attendance-modal">Edit</button>';
                }
                if(auth()->user()->can('delete attendance'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger delete-attendance" id="'.$attendance->id.'">Delete</button>';
                }
                return $action;
            })
            ->setRowClass(function($attendance){
                $scheduled_time_in = Carbon::parse($attendance->time_in)->format('Y-m-d').' '.$attendance->schedule->time_in;
                return $this->late_counter_in_minutes($attendance->time_in, $scheduled_time_in) > 0 ? 'late' : '';
            })
            ->rawColumns(['action','is_overtime_allowed','name'])
            ->with([
                'total_net_pay' => !is_null($employee_id) ? number_format($this->get_payroll_net_pay($employee->biometric->userid, $startDate, $endDate),2) : null,
                'start_date' => $request->session()->get('attendance_start_date'),
                'end_date' => $request->session()->get('attendance_end_date')
            ])
            ->make(true);
    }
}
