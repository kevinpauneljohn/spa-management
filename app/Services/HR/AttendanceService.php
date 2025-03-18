<?php

namespace App\Services\HR;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\UserService;
use Carbon\Carbon;
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
        $attendance->time_in = !empty($data['time_in']) ? Carbon::parse($data['time_in'])->format('Y-m-d H:i:s') : null;
        $attendance->time_out = !empty($data['time_out']) ? Carbon::parse($data['time_out'])->format('Y-m-d H:i:s') : null;
        $attendance->break_in = !empty($data['break_in']) ? Carbon::parse($data['break_in'])->format('Y-m-d H:i:s') : null;
        $attendance->break_out = !empty($data['break_out']) ? Carbon::parse($data['break_out'])->format('Y-m-d H:i:s') : null;
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

        return $attendance->save();
    }

    private function timeOut($time_out, $biometrics_userid): bool
    {
        $attendance = Attendance::where('userid', $biometrics_userid)->where('time_in', '!=', null)->where('time_out',null);
        if($attendance->count() > 0)
        {
            $attendance = $attendance->first();
            $attendance->time_out = $time_out;
            $attendance->save();
            return true;
        }
        return false;
    }

    public function late_counter_in_minutes($time_in, $schedule_time_in)
    {
        $late_in_minutes = $this->getTotalMinutes($schedule_time_in, $time_in);
        return max(number_format($late_in_minutes,2), 0);
    }

    public function get_total_overtime($time_out, $schedule_time_out)
    {
        $total_overtime = Carbon::parse($schedule_time_out)->diffInHours($time_out,false);
        return max(number_format($total_overtime,2), 0);
    }

    private function getBiometricUserId()
    {
        return collect(Employee::where('owner_id',$this->userService->get_staff_owner()->id)->get())->map(function($item, $key){
            return collect($item)->merge(['biometrics_user_id' => Employee::find($item['id'])->biometric->userid]);
        })->pluck('biometrics_user_id');
    }

    public function employeeAttendance($employee_id)
    {
        if(is_null($employee_id))
        {
            $employees = $this->getBiometricUserId();
            $attendances = Attendance::whereIn('userid',$employees)->get();
        }
        else{
            $employee = Employee::find($employee_id);
            $attendances = Attendance::where('userid',$employee->biometric->userid)->get();
        }

        return DataTables::of($attendances)
            ->addColumn('name',function($attendance){
                return ucwords($attendance->getEmployeeName());
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
            ->editColumn('is_overtime_allowed', function($attendance){
                return $attendance->is_overtime_allowed ? '<span class="badge badge-success">Approved</span>' : '';
            })
            ->editColumn('total_overtime', function($attendance){
                $scheduled_time_out = Carbon::parse($attendance->time_out)->format('Y-m-d').' '.$attendance->schedule->time_out;
                return $this->get_total_overtime($attendance->time_out, $scheduled_time_out);
            })
            ->editColumn('updated_at', function($attendance){
                return $attendance->updated_at->format('Y-m-d h:i:s a');
            })
            ->editColumn('user_id', function($attendance){
                return !is_null($attendance->user) ? ucwords($attendance->user->fullname) : '';
            })
            ->addColumn('action', function($attendance){
                $action = '';
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
            ->rawColumns(['action','is_overtime_allowed'])
            ->make(true);
    }
}
