<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Appointment;
use App\Models\Transaction;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {

    }

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
        $client->firstname = ucfirst($client->firstname);
        $client->middlename = ucfirst($client->middlename);
        $client->lastname = ucfirst($client->lastname);
        return response()->json(['client' => $client]);
    }

    public function filter(Request $request, $id, $spa)
    {
        $spaData = Spa::findOrFail($spa);
        $owner = Owner::find($spaData->owner_id);
        if($request->ajax())
        {
             $client = Client::where('firstname', 'LIKE', '%'.$request->search.'%')
            ->orWhere('middlename', 'LIKE', '%'.$request->search.'%')
            ->orWhere('lastname', 'LIKE', '%'.$request->search.'%')
            ->get();

            $data = [];
            $status = false;
            $count = 0;
            if ($client) {
                $check_owner = $owner->clients()->first();
                if ($check_owner) {
                    foreach ($client as $key => $list) {
                        $check_appointment = $this->checkInAppointment($list->id, $spa);
                        $check_transaction = $this->checkInTransaction($list->id, $spa);

                        if($list->id != $check_appointment && $list->id != $check_transaction) {
                            $data [ucfirst($list->firstname).' '.ucfirst($list->lastname). ' [0'.$list->mobile_number.']'] = $list->id;
                        }
                    }

                    if (!empty($data)) {
                        $status = true;
                        $count = count($data);
                    }
                }
            }

            $response = [
                'status'   => $status,
                'data'   => $data,
                'count' => $count,
            ];

            return $response;
        }
    }

    public function checkInAppointment($id, $spa)
    {
        $appointment = Appointment::where([
            'client_id' => $id,
            'spa_id' => $spa,
            'appointment_status' => 'reserved'
        ])->first();

        $val = '';
        if ($appointment) {
            $val = $appointment->client_id;
        }

        return $val;
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
            if ($get_latest_transaction->start_time > $now) {
                $now = $get_latest_transaction->start_time;
            }
        }

        $transaction = Transaction::where('client_id', $id)
            ->where('spa_id', $spa_id)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->first();

        $id = 0;
        if (!empty($transaction)) {
            $id = $transaction->client_id;
        }

        return $id;
    }

    public function checkClientExist(Request $request)
    {
        if($request->ajax())
        {
            $firstname = $request->firstname;
            $lastname = $request->lastname;
            $mobileNo = $request->mobile_number;

             $client = Client::where(
                'firstname', 'LIKE', '%'.$firstname.'%'
            )->where(
                'lastname', 'LIKE', '%'.$firstname.'%'
            )->where(
                'mobile_number', $mobileNo
            )->first();

            $status = false;
            $data = [];
            if ($client) {
                $response = [
                    'status'   => $status,
                    'data'   => $data,
                ];
            }

            return $response;
        }
    }
}
