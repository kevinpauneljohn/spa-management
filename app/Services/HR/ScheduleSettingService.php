<?php

namespace App\Services\HR;

use App\Models\Biometric;
use App\Models\ScheduleSetting;

class ScheduleSettingService extends BenefitService
{
    public function saveSettings($owner_id, $days_of_work, $schedule_id, $employee_id): array
    {
        $schedule_setting_saved = ScheduleSetting::updateOrCreate(
            ['employee_id' => $employee_id],
            [
                'owner_id' => $owner_id,
                'days_of_work' => collect($days_of_work)->toArray(),
                'schedule_id' => $schedule_id,
                'employee_id' => $employee_id
            ],
        );

        if($schedule_setting_saved)
        {
            return ['success' => true, 'message' => 'Settings saved successfully'];
        }
        return ['success' => false, 'message' => 'Settings not saved'];
    }

    public function is_employee_have_saved_schedule($employee_id): bool
    {
        return ScheduleSetting::where('employee_id', $employee_id)->count() > 0;
    }

    public function get_employee_id($biometric_user_id)
    {
        return Biometric::where('userid',$biometric_user_id)->first()->employee_id;
    }

    public function get_schedule_id($biometric_user_id)
    {
        return ScheduleSetting::where('employee_id',$this->get_employee_id($biometric_user_id))->first()->schedule_id;
    }
}
