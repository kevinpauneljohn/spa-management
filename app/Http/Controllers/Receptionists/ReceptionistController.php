<?php

namespace App\Http\Controllers\Receptionists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Service;
use App\Models\Therapist;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Transaction;

class ReceptionistController extends Controller
{
    public function index()
    {
        $request = request()->filled('id');
        if ($request) {
            $spa_id = request()->id;
        } else {
            $id = auth()->user()->id;
            $user = User::findOrFail($id);
    
            $spa_id = '';
            if (!empty($user->spa_id)) {
                $spa_id = $user->spa_id;
            }
        }

        $spa = Spa::findOrFail($spa_id);

        $title = $spa->name;
        return view('Receptionist.index',['title' => $title, 'spa_id' => $spa_id, 'total_rooms' => $spa->number_of_rooms, 'owner_id' => $spa->owner_id]);
    }

    public function lists($id)
    {
        $spa = Spa::findOrFail($id);
        $rooms = range(1, $spa->number_of_rooms);

        $data = [];
        foreach ($rooms as $room) {
            $data [] = $this->getData($room, $id);
        }

        return $data;
    }
    
    public function getData($room, $spa_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = Transaction::where(
            'room_id', $room
        )->where('spa_id', $spa_id)->where(
            'end_time', '>=', $now
        )->with(['client'])->first();
        
        $dataList = [];
        $isAvailable = true;
        $isColorSet = 'bg-success';
        if (!empty($transaction)) {
            // $dataList = $transaction;
            $start_time_formatted = date('h:i:s A', strtotime($transaction->start_time));
            $end_time_formatted = date('h:i:s A', strtotime($transaction->end_time));

            $dataList = [
                'id' => $transaction->id,
                'spa_id' => $transaction->spa_id,
                'sales_id' => $transaction->sales_id,
                'room_id' => $transaction->room_id,
                'client_id' => $transaction->client_id,
                'client' => [
                    'id' => $transaction->client['id'],
                    'firstname' => ucfirst($transaction->client['firstname']),
                    'middlename' => ucfirst($transaction->client['middlename']),
                    'lastname' => ucfirst($transaction->client['lastname']),
                    'date_of_birth' => $transaction->client['date_of_birth'],
                    'mobile_number' => $transaction->client['mobile_number'],
                    'email' => $transaction->client['email'],
                    'address' => $transaction->client['address'],
                    'client_type' => $transaction->client['client_type'],
                ],
                'service_id' => $transaction->service_id,
                'service_name' => $transaction->service_name,
                'amount' => $transaction->amount,
                'therapist_1' => $transaction->therapist_1,
                'therapist_2' => $transaction->therapist_2,
                'start_time' => $transaction->start_time,
                'end_time' => $transaction->end_time,
                'start_and_end_time' => $start_time_formatted.' to '.$end_time_formatted,  
                'plus_time' => $transaction->plus_time,
                'discount_rate' => $transaction->discount_rate,
                'discount_amount' => $transaction->discount_amount,
                'tip' => $transaction->idtip,
                'rating' => $transaction->rating,
                'sales_type' => $transaction->sales_type,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ];
            $isAvailable = false;
            $isColorSet = 'bg-secondary';
        }

        $data = [
            'room_id' => $room,
            'data' => $dataList,
            'is_available' => $isAvailable,
            'is_color_set' => $isColorSet
        ];

        return $data;
    }

    public function getServices($id)
    {
        $service = Service::where('spa_id', $id)->pluck('id', 'name');

        return $service;
    }

    public function plusTime()
    {
        $range = range(15, 300, 15);

        $data = [];
        foreach ($range as $ranges) {
            $data [$ranges] = $ranges;
        }
        return $data;
    }

    public function getTherapist($id)
    {
        $therapist = Therapist::where('spa_id', $id)->with(['user'])->get();
        $data = [];
        foreach ($therapist as $list) {
            $data [ucfirst($list->user['firstname']).' '.ucfirst($list->user['lastname'])] = $list->id;
        }

        return $data;
    }

    public function store(Request $request, $id, $amount)
    {
        $spa_id = $id;
        $amount_paid = $amount;
        $payment_status = 'pending';
        $user_id = auth()->user()->id;
        $data = $request['value'];

        $code = 201;
        $status = false;
        $message = 'Failed to add sales and client information.';
        $sale = Sale::create([
            'spa_id' => $spa_id,
            'amount_paid' => $amount_paid,
            'payment_status' => $payment_status,
            'user_id' => $user_id
        ]);
        
        if ($sale) {
            $sale_id = $sale->id;
            $client = $this->saveClient($spa_id, $data, $sale_id);

            if ($client) {
                $status = true;
                $message = 'Sales and Client Information successfully saved.';
            }
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function saveClient($spa_id, $data, $sale_id)
    {
        $response = false;
        if (!empty($data)) {
            $client_id = [];
            foreach ($data as $list) {
                if ($list['value_client_type'] == 'new') {
                    $client = Client::create([
                        'firstname' => $list['value_first_name'],
                        'middlename' => $list['value_middle_name'],
                        'lastname' => $list['value_last_name'],
                        'date_of_birth' => $list['value_date_of_birth'],
                        'mobile_number' => $list['value_mobile_number'],
                        'email' => $list['value_email'],
                        'address' => $list['value_address'],
                        'client_type' => $list['value_client_type'],
                    ]);
                } else {
                    $client = Client::findOrFail($list['existing_user_id']);
                    $client->firstname = $list['value_first_name'];
                    $client->middlename = $list['value_middle_name'];
                    $client->lastname = $list['value_last_name'];
                    $client->date_of_birth = $list['value_date_of_birth'];
                    $client->mobile_number = $list['value_mobile_number'];
                    $client->email = $list['value_email'];
                    $client->address = $list['value_address'];
                    $client->client_type = $list['value_client_type'];

                    $client->save();
                }

                if ($client) {
                    $client_id = $client->id;

                    $start_time_val = date('Y-m-d H:i:s', strtotime($list['value_start_time']));

                    $therapist = [$list['value_therapist_1']];
                    if (!empty($list['value_therapist_2'])) {
                        $therapist = [$list['value_therapist_1'], $list['value_therapist_2']];
                    }
    
                    foreach ($therapist as $key => $data_list) {
                        if ($key == 1) {
                            $amount = 0;
                        } else {
                            $amount = $list['price'];
                        }
    
                        $transaction = Transaction::create([
                            'spa_id' => $spa_id,
                            'service_id' => $list['value_services'],
                            'service_name' => $list['value_services_name'],
                            'amount' => $amount,
                            'therapist_1' => $data_list,
                            'client_id' => $client_id,
                            'start_time' => $start_time_val,
                            'end_time' => $this->getEndTime($list['value_services'], $start_time_val, $list['value_plus_time']),
                            'plus_time' => $list['value_plus_time'],
                            'discount_rate' => null,
                            'discount_amount' => null,
                            'tip' => null,
                            'rating' => 0,
                            'sales_type' => 'walk-in',
                            'sales_id' => $sale_id,
                            'room_id' => $list['value_room_id'],
                        ]);
                    }
    
                    if ($transaction) {
                        $response = true;
                    }
                }
            }
        }

        return $response;
    }

    public function roomRange($num)
    {
        $rooms = range(1, $num);

        return $rooms;
    }

    public function update(Request $request, $id, $amount)
    {
        $sales_id = $request->sales_id;
        $spa_id = $id;
        $client_id = $request->client_id;
        $transaction_id = $request->id;
        $new_amount = $amount;
        $old_amount = $request->old_amount;

        $sale = Sale::findOrFail($sales_id);
        $amount_paid = $sale->amount_paid;
        $sale->amount_paid = ($amount_paid + $new_amount) - $old_amount;

        $client_data = [
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'date_of_birth' => $request->date_of_birth,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'address' => $request->address,
            'client_type' => $request->client_type,
        ];

        $transaction_data = [
            'spa_id' => $spa_id,
            'client_id' => $client_id,
            'sales_id' => $sales_id,
            'service_id' => $request->service_id,
            'service_name' => $request->service_name,
            'therapist_1' => $request->therapist_1,
            'therapist_2' => $request->therapist_2,
            'therapist_2_transaction_id' => $request->therapist_2_id,
            'start_time' => $request->start_time,
            'plus_time' => $request->plus_time,
            'room_id' => $request->room_id,
            'amount' => $new_amount,
        ];

        $status = false;
        if ($sale->save()) {
            $updateClient = $this->updateClient($client_id, $client_data);
            if ($updateClient) {
                $updateTransaction = $this->updateTransaction($transaction_id, $transaction_data);
                if ($updateTransaction) {
                    $status = true;
                } else {
                    $message = 'Unable to save Transaction information. Please try again.';
                }
            } else {
                $message = 'Unable to save Client information. Please try again.';
            }
        } else {
            $message = 'Unable to save Sales information. Please try again.';
        }

        if ($status) {
            $message = 'Sales & Reservation information has been successfully saved.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function updateClient($id, $data)
    {
        $client = Client::findOrFail($id);
        $client->firstname = $data['firstname'];
        $client->middlename = $data['middlename'];
        $client->lastname = $data['lastname'];
        $client->date_of_birth = $data['date_of_birth'];
        $client->mobile_number = $data['mobile_number'];
        $client->email = $data['email'];
        $client->address = $data['address'];
        $client->client_type = $data['client_type'];

        $status = false;
        if($client->save()){
            $status = true;
        } 
        // if($client->isDirty()){
        //     $client->save();
        //     $status = true;
        // } 

        return $status;
    }

    public function updateTransaction($id, $data)
    {
        $start_time_val = date('Y-m-d H:i:s', strtotime($data['start_time']));
        $therapist1 = Transaction::findOrFail($id);
        $status = false;
        $status_2 = false;
        if (!empty($data['therapist_2_transaction_id'])) {
            $therapist2 = Transaction::findOrFail($data['therapist_2_transaction_id']);
            if (!empty($data['therapist_2'])) {
                $therapist2->service_id = $data['service_id'];
                $therapist2->service_name = $data['service_name'];
                $therapist2->amount = 0;
                $therapist2->therapist_1 = $data['therapist_2'];
                $therapist2->start_time = $start_time_val;
                $therapist2->end_time = $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']);
                $therapist2->plus_time = $data['plus_time'];
                $therapist2->room_id = $data['room_id'];
        
                if($therapist2->save()){
                    $status_2 = true;
                }
            } else {
                if ($therapist2->delete()) {
                    $status_2 = true;
                }
            }
        } else {
            if (!empty($data['therapist_2'])) {
                $transaction = Transaction::create([
                    'spa_id' => $data['spa_id'],
                    'service_id' => $data['service_id'],
                    'service_name' => $data['service_name'],
                    'amount' => 0,
                    'therapist_1' => $data['therapist_2'],
                    'client_id' => $data['client_id'],
                    'start_time' => $start_time_val,
                    'end_time' => $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']),
                    'plus_time' => $data['plus_time'],
                    'discount_rate' => null,
                    'discount_amount' => null,
                    'tip' => null,
                    'rating' => 0,
                    'sales_type' => 'walk-in',
                    'sales_id' => $data['sales_id'],
                    'room_id' => $data['room_id'],
                ]);
    
                if ($transaction) {
                    $status_2 = true;
                }
            } else {
                $status_2 = true;
            }
        }

        if ($status_2) {
            $therapist1->service_id = $data['service_id'];
            $therapist1->service_name = $data['service_name'];
            $therapist1->amount = $data['amount'];
            $therapist1->therapist_1 = $data['therapist_1'];
            $therapist1->start_time = $start_time_val;
            $therapist1->end_time = $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']);
            $therapist1->plus_time = $data['plus_time'];
            $therapist1->room_id = $data['room_id'];
    
            if($therapist1->save()){
                $status = true;
            } 
        }

        return $status;
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
}

