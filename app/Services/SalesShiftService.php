<?php

namespace App\Services;
use App\Models\SalesShift;
use Carbon\Carbon;

class SalesShiftService
{
    public function get_shift($spa_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $shift = SalesShift::where([
            'spa_id' => $spa_id,
            'user_id' => auth()->user()->id
        ])->orderBy('id', 'DESC')->first();

        $status = false;
        $is_shift_today = false;
        $is_end_shift = false;
        $is_money_confirm = false;
        if (!empty($shift)) {
            $status = true;
            if (date('Y-m-d', strtotime($shift->start_shift)) == date('Y-m-d', strtotime($now))) {
                $is_shift_today = true;
            }

            if ($shift->confirm_end_shift == 'yes') {
                $is_end_shift = true;
            }

            if ($shift->confirm_start_money == 'yes') {
                $is_money_confirm = true;
            }
        }

        $response = [
            'status' => $status,
            'data' => $shift,
            'shift_today' => $is_shift_today,
            'end_shift' => $is_end_shift,
            'money_confirm' => $is_money_confirm
        ];

        return $response;
    }

    public function start_shift($spa_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $shift = SalesShift::create([
            'spa_id' => $spa_id,
            'user_id' => auth()->user()->id,
            'start_shift' => $now,
            'start_money' => 0,
            'confirm_start_shift' => 'yes'
        ]);

        $status = false;
        $message = 'Unable to start shift. Please try again.';
        $data = [];
        if ($shift) {
            $status = true;
            $data = $shift;    
            $message = 'Shift has been started.';
        }

        $response = [
            'status' => $status,
            'data' => $data,
            'message' => $message
        ];

        return $response;
    }

    public function start_money($id, $amount)
    {
        $shift = SalesShift::findOrFail($id);
        $shift->start_money = $amount;
        $shift->confirm_start_money = 'yes';
        
        $status = false;
        $message = 'Unable to add start money. Please try again.';
        if ($shift->save()) {
            $status = true;
            $message = 'Start money has been added.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ];

        return $response;
    }

    public function end_shift($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $shift = SalesShift::findOrFail($id);
        $shift->end_shift = $now;
        $shift->confirm_end_shift = 'yes';
        
        $status = false;
        $message = 'Unable to end shift. Please try again.';
        if ($shift->save()) {
            $status = true;
            $message = 'Shift has been ended.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ];

        return $response;
    }
}