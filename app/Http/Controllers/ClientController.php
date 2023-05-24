<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\Transaction;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function getList()
    {
        $client = Client::get();
        
        $data = [];
        foreach ($client as $list) {
            $data [ucfirst($list->firstname).' '.ucfirst($list->lastname).' ['.$list->mobile_number.']'] = $list->id;
        }
        
        return $data;
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    public function filter($id, $spa)
    {
         $client = Client::where('firstname', 'LIKE', '%'.$id.'%')
            ->orWhere('middlename', 'LIKE', '%'.$id.'%')
            ->orWhere('lastname', 'LIKE', '%'.$id.'%')
            ->get();

        $data = [];
        $status = false;
        $count = 0;
        if ($client->count() > 0) {
            foreach ($client as $list) {
                $check_appointment = $this->checkInAppointment($list->id);
                $check_transaction = $this->checkInTransaction($list->id, $spa);
                if ($check_appointment < 1 && $check_transaction < 1) {
                    $data [ucfirst($list->firstname).' '.ucfirst($list->lastname)] = $list->id;
                }
            }

            if (!empty($data)) {
                $status = true;
                $count = count($data);
            }
        }

        $response = [
            'status'   => $status,
            'data'   => $data,
            'count' => $count
        ]; 

        return $response;
    }

    public function checkInAppointment($id)
    {
        $appointment = Appointment::where('client_id', $id)->where('appointment_status', 'reserved')->count();

        return $appointment;
    }

    public function checkInTransaction($id, $spa_id)
    {
        $get_latest_transaction = Transaction::where('client_id', $id)
            ->where('spa_id', $spa_id)
            ->where('amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $from = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        if (!empty($get_latest_transaction)) {
            $from = $get_latest_transaction->end_time;
        }

        $transaction = Transaction::where('client_id', $id)
            ->where('spa_id', $spa_id)
            ->whereDate('end_time', '=', Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d'))
            ->where('end_time', '>', Carbon::now()->setTimezone('Asia/Manila')->format('H:i:s'))
            ->count();

        $count = 0;
        if ($transaction > 0) {
            $count = 1;
        }
        
        return $count;
    }
}
