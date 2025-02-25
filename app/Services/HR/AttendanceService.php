<?php

namespace App\Services\HR;

use App\Models\Attendance;

class AttendanceService
{
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

    private function timeIn($time_in, $biometrics_userid): bool
    {
        $attendance = new Attendance();
        $attendance->time_in = $time_in;
        $attendance->userid = $biometrics_userid;

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
}
