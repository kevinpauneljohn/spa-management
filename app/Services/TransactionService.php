<?php

namespace App\Services;
use App\Models\Transaction;
use App\Models\Service;
use Carbon\Carbon;

class TransactionService
{
    public function get_transaction($client_id, $spa_id, $dateTime)
    {
        $transaction = Transaction::where('client_id', $client_id)
            ->where('spa_id', $spa_id)
            ->where('start_time', '<=', $dateTime)
            ->where('end_time', '>=', $dateTime)
            ->first();

        $status = false;
        $data = [];
        if ($transaction) {
            $status = true;
            $data = $transaction;    
        }

        $response = [
            'status' => $status,
            'data' => $data,
        ]; 

        return $response;
    }

    public function create($spa_id, $client_id, $sales_id, $data)
    {
        $status = false;
        $data_array = [$data['therapist_1']];
        if (!empty($data['therapist_2'])) {
            $data_array = [$data['therapist_1'], $data['therapist_2']];
        }

        foreach ($data_array as $key => $data_arrays) {
            $therapist = $data['therapist_1'];
            $amount = $data['price'];
            if ($key == 1) {
                $therapist = $data['therapist_2'];
                $amount = 0;
            }

            $start_time_val = date('Y-m-d H:i:s', strtotime($data['start_time']));
            $transaction = Transaction::create([
                'spa_id' => $spa_id,
                'service_id' => $data['service_id'],
                'service_name' => $data['service_name'],
                'amount' => $amount,
                'therapist_1' => $therapist,
                'client_id' => $client_id,
                'start_time' => $start_time_val,
                'end_time' => $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']),
                'plus_time' => $data['plus_time'],
                'discount_rate' => NULL,
                'discount_amount' => NULL,
                'tip' => NULL,
                'rating' => 0,
                'sales_type' => $data['appointment_type'],
                'sales_id' => $sales_id,
                'room_id' => $data['room_id']
            ]);

            if ($transaction) {
                $status = true;
            }
        }

        return $status;
    }

    public function update()
    {

    }

    public function getEndTime($id, $start_time, $plus_time = null)
    {
        $start_time_val = date('Y-m-d H:i:s', strtotime($start_time));

        $plus_time_val = 0;
        if (!empty($plus_time)) {
            $plus_time_val = $plus_time;
        }

        $service = Service::findOrFail($id);

        $end_time = [];
        $result = '00:00:00';
        if (!empty($service)) {
            $duration = $service->duration;

            $total_duration = $duration + $plus_time_val;
            $total_duration_in_seconds = $total_duration * 60;
            $converted_duration_time = gmdate("H:i:s", $total_duration_in_seconds);

            $get_duration = strtotime($converted_duration_time)-strtotime("00:00:00");
            $result = date("Y-m-d H:i:s", strtotime($start_time_val)+$get_duration);
        }
 
        return $result;
    }

    public function therapistCount($therapist_id)
    {
        $yesterday_start = Carbon::now()->setTimezone('Asia/Manila')->subDays(2)->format('Y-m-d 00:00:01');
        $yesterday_end = Carbon::now()->setTimezone('Asia/Manila')->subDays(2)->format('Y-m-d 23:59:59');
        $transaction = Transaction::where('therapist_1', $therapist_id)
            ->where('amount', '>', 0)
            ->where('start_time', '>=', $yesterday_start)
            ->where('end_time', '<=', $yesterday_end)
            ->count();

        return $transaction;
    }

    public function therapistAvailability($spa_id, $therapist_id, $dateTime)
    {
        $transaction = Transaction::where('therapist_1', $therapist_id)
            ->where('amount', '>', 0)
            ->where('spa_id', $spa_id)
            ->where('start_time', '<=', $dateTime)
            ->where('end_time', '>=', $dateTime)
            ->first();

        $status = true;
        $data = [];
        if ($transaction) {
            $status = false;   
        }

        return $status;
    }
}