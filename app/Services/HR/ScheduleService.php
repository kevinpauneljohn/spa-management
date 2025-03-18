<?php

namespace App\Services\HR;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ScheduleService extends ScheduleSettingService
{
    public function saveSchedule($name, $time_in, $time_out, $break_in, $break_out, $owner_id, $user_id): array
    {
        $schedule = Schedule::create([
            'name' => $name,
            'time_in' => $time_in,
            'time_out' => $time_out,
            'break_in' => $break_in,
            'break_out' => $break_out,
            'owner_id' => $owner_id,
            'user_id' => $user_id
        ]);
        if ($schedule) {
            return ['success' => true, 'message' => 'Schedule has been saved'];
        }
        return ['success' => false, 'message' => 'Failed to save schedule'];
    }

    public function updateSchedule($id, $name, $time_in, $time_out, $break_in, $break_out, $user_id): array
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->name = $name;
        $schedule->time_in = $time_in;
        $schedule->time_out = $time_out;
        $schedule->break_in = $break_in;
        $schedule->break_out = $break_out;
        $schedule->user_id = $user_id;

        if($schedule->isDirty())
        {
            if($schedule->save())
            {
                return ['success' => true, 'message' => 'Schedule has been updated'];
            }
            return ['success' => false, 'message' => 'Failed to update schedule'];
        }
        return ['success' => false, 'message' => 'No changes have been made'];
    }

    public function getSchedules($owner_id)
    {
        $schedules = Schedule::where('owner_id',$owner_id)->get();
        return DataTables::of($schedules)
            ->editColumn('user_id', function ($schedule) {
                return ucwords(strtolower(User::findOrFail($schedule->user_id)->fullname));
            })
            ->editColumn('updated_at', function ($schedule) {
                return $schedule->updated_at->format('Y-m-d h:i:s a');
            })
            ->editColumn('time_in', function ($schedule) {
                return Carbon::parse($schedule->time_in)->format('h:i A');
            })
            ->editColumn('time_out', function ($schedule) {
                return Carbon::parse($schedule->time_out)->format('h:i A');
            })
            ->editColumn('break_in', function ($schedule) {
                return Carbon::parse($schedule->break_in)->format('h:i A');
            })
            ->editColumn('break_out', function ($schedule) {
                return Carbon::parse($schedule->break_out)->format('h:i A');
            })
            ->addColumn('total_hours', function ($schedule) {
                return $this->getTotalHours($schedule->time_in, $schedule->time_out);
            })
            ->addColumn('total_break_in_minutes', function ($schedule) {
                return $this->getTotalMinutes($schedule->break_in, $schedule->break_out);
            })
            ->addColumn('total_hours_less_break', function ($schedule) {
                return $this->getTotalHoursLessBreak($schedule->time_in, $schedule->time_out, $schedule->break_in, $schedule->break_out);
            })
            ->addColumn('action', function($schedule){
                $action = '';
                if(auth()->user()->can('edit schedule'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 edit-schedule" id="'.$schedule->id.'">Edit</button>';
                }
                if(auth()->user()->can('delete schedule'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger delete-schedule" id="'.$schedule->id.'">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getTotalHours($time_in, $time_out): int
    {
        $time_in = Carbon::parse($time_in);
        $time_out = Carbon::parse($time_out);
        return $time_in->diffInHours($time_out, false);
    }

    public function getTotalMinutes($time_in, $time_out): int
    {
        $time_in = Carbon::parse($time_in);
        $time_out = Carbon::parse($time_out);
        return $time_in->diffInMinutes($time_out, false);
    }

    public function getTotalHoursLessBreak($time_in, $time_out, $break_in, $break_out): string
    {
        $total_hours_in_minutes = $this->convertHoursIntoMinutes($this->getTotalHours($time_in, $time_out));
        $total_break_in_minutes = $this->getTotalMinutes($break_in, $break_out);
        $total_hours_less_break_in_minutes = $total_hours_in_minutes - $total_break_in_minutes;
        return $this->convertMinutesIntoHours($total_hours_less_break_in_minutes);
    }

    public function convertHoursIntoMinutes($total_hours)
    {
        return $total_hours * 60;
    }

    public function convertMinutesIntoHours($total_minutes)
    {
        return $total_minutes / 60;
    }

}
