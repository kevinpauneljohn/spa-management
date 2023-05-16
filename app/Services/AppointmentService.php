<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Service;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AppointmentService
{
    public function data($id)
    {
        $appointment = Appointment::where(['spa_id' => $id, 'appointment_status' => 'reserved'])->with(['client'])->get();

        return DataTables::of($appointment)
            ->editColumn('client',function($appointment){
                return ucfirst($appointment->client->firstname).' '.ucfirst($appointment->client->lastname);
            })
            ->editColumn('service',function ($appointment){

                return $appointment->service_name;
            })
            ->editColumn('batch',function ($appointment){

                return 'Batch # '.$appointment->batch;
            })
            ->editColumn('amount',function ($appointment){

                return '&#8369; '.$appointment->amount;
            })
            ->editColumn('type',function ($appointment){
                $status = "";
                if ($appointment->appointment_type == 'Social Media') {
                    $status .= $appointment->appointment_type.'<br />';
                    $status .= '('.$appointment->social_media_type.')';
                } else {
                    $status = $appointment->appointment_type;
                }

                return $status;
            })
            ->editColumn('status',function ($appointment){
                return ucfirst($appointment->appointment_status);
            })
            ->editColumn('date',function ($appointment){
                $createdAt = Carbon::parse($appointment->created_at);
                $created_at = $createdAt->setTimezone('Asia/Manila')->format('F d, Y g:i A');

                return $created_at;
            })
            ->addColumn('action', function($appointment){
                $batch_id = $appointment->batch;
                $action = "";
                if (auth()->user()->can('view sales')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-warning view-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-eye"></i></a>&nbsp;';
                }

                if (auth()->user()->can('edit sales')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-primary edit-appointment-btn" id="'.$appointment->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }

                if (auth()->user()->can('move sales')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-success move-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-exchange-alt"></i></a>&nbsp;';
                }

                if (auth()->user()->can('delete sales')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-danger delete-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }

                return $action;
            })
            ->rawColumns(['action','client','batch','amount','type','date'])
            ->make(true);
    }

    public function appointmentCreateSales($data, $id, $amount)
    {
        $code = 201;
        $status = false;
        $message = 'Unable to save appointments. Please try again.';
        if (!empty($data)) {
            $sale = Sale::create([
                'spa_id' => $id,
                'amount_paid' => $amount,
                'payment_status' => 'pending',
                'user_id' => auth()->user()->id
            ]);

            if ($sale) {
                $sale_id = $sale->id;
                foreach ($data->value as $list) {
                    $client_data = [
                        'firstname' => $list['value_first_name'],
                        'middlename' => $list['value_middle_name'],
                        'lastname' => $list['value_last_name'],
                        'date_of_birth' => $list['value_date_of_birth'],
                        'mobile_number' => $list['value_mobile_number'],
                        'email' => $list['value_email'],
                        'address' => $list['value_address'],
                        'client_type' => $list['value_client_type'],
                    ];

                    if (!empty($list['existing_user_id'])) {
                        $client = $this->saveClient($list['existing_user_id'], $client_data);
                    } else {
                        $client = $this->saveClient(0, $client_data);
                    }

                    $transaction_data = [
                        'spa_id' => $id,
                        'service_id' => $list['value_services'],
                        'service_name' => $list['value_services_name'],
                        'amount' => $list['total_price'],
                        'therapist_1' => $list['therapist_1'],
                        'therapist_2' => $list['therapist_2'],
                        'client_id' => $client,
                        'start_time' => $list['value_start_time'],
                        'end_time' => '',
                        'plus_time' => $list['plus_time'],
                        'discount_rate' => '',
                        'discount_amount' =>'',
                        'tip' => '',
                        'rating' => 0,
                        'sales_type' => $list['value_appointment_type'],
                        'sales_id' => $sale_id,
                        'room_id' => $list['room_id'],
                    ];

                    $transactionStatus = $this->createTransaction($transaction_data);

                    $status = true;
                    $message = 'Sales and Client Information successfully saved.';
                }
            }
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function create($data, $id)
    {
        $code = 201;
        $status = false;
        $message = 'Unable to save appointments. Please try again.';
        if (!empty($data)) {
            $batch = Appointment::select('batch')->where('spa_id', $id)->orderBy('batch', 'DESC')->first();
            
            $batchNumber = 1;
            if (!empty($batch)) {
                $batchNumber = $batch['batch'] + 1;
            }

            foreach ($data['value'] as $list) {
                $client_data = [
                    'firstname' => $list['value_first_name'],
                    'middlename' => $list['value_middle_name'],
                    'lastname' => $list['value_last_name'],
                    'date_of_birth' => $list['value_date_of_birth'],
                    'mobile_number' => $list['value_mobile_number'],
                    'email' => $list['value_email'],
                    'address' => $list['value_address'],
                    'client_type' => $list['value_client_type'],
                ];

                if (!empty($list['existing_user_id'])) {
                    $client = $this->saveClient($list['existing_user_id'], $client_data);
                } else {
                    $client = $this->saveClient(0, $client_data);
                }

                $start_time = '';
                if (!empty($list['value_start_time'])) {
                    $start_time = date('Y-m-d H:i:s', strtotime($list['value_start_time']));
                }

                $appointment = Appointment::create([
                    'spa_id' => $id,
                    'client_id' => $client,
                    'service_id' => $list['value_services'],
                    'service_name' => $list['value_services_name'],
                    'batch' => $batchNumber,
                    'amount' => $list['price'],
                    'start_time' => $start_time,
                    'appointment_type' => $list['value_appointment_type'],
                    'social_media_type' => $list['value_social_type'],
                    'appointment_status' => 'reserved',
                ]);

                $status = true;
                $message = 'Appointments has been successfully saved.';
            }
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function saveClient($id, $data)
    {
        $status = $id;
        if ($id == 0) {
            $client = Client::create([
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'date_of_birth' => $data['date_of_birth'],
                'mobile_number' => $data['mobile_number'],
                'email' => $data['email'],
                'address' => $data['address'],
                'client_type' => $data['client_type'],
            ]);

            $status = $client->id;
        } else {
            $client = Client::findOrFail($id);

            $client->firstname = $data['firstname'];
            $client->middlename = $data['middlename'];
            $client->lastname = $data['lastname'];
            $client->date_of_birth = $data['date_of_birth'];
            $client->mobile_number = $data['mobile_number'];
            $client->email = $data['email'];
            $client->address = $data['address'];
            $client->client_type = $data['client_type'];

            $client->save();

            $status = $id;
        }

        return $status;
    }

    public function view($id)
    {
        $appointment = Appointment::with(['client'])->findOrFail($id);
        $appointment->start_time = date('d F Y, h:i A', strtotime($appointment->start_time));
        return $appointment;
    }

    public function getUpcoming($id)
    {
        $appointment = Appointment::where(['spa_id' => $id, 'appointment_status' => 'reserved'])->get()->count();

        return $appointment;
    }

    public function update($data, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $start_time = '';
        if (!empty($data->start_time)) {
            $start_time = date('Y-m-d H:i:s', strtotime($data->start_time));
        }

        $appointment->service_id = $data->value_services;
        $appointment->service_name = $data->value_services_name;
        $appointment->amount = $data->price;
        $appointment->start_time = $start_time;
        $appointment->appointment_type = $data->appointment_type;
        $appointment->social_media_type = $data->appointment_social;

        $client_data = [
            'firstname' => $data->firstname,
            'middlename' => $data->middlename,
            'lastname' => $data->lastname,
            'date_of_birth' => $data->date_of_birth,
            'mobile_number' => $data->mobile_number,
            'email' => $data->email,
            'address' => $data->address,
        ];

        $status = false;
        $message = 'Unable to update Appointment. Please try again.';
        if ($appointment->save()) {
            $client_info = $this->updateClientInfo($data->client_id, $client_data);

            if ($client_info) {
                $status = true;
                $message = 'Appointment has been successfully updated.';
            }
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function updateClientInfo($id, $data)
    {
        $client = Client::findOrFail($id);
        $client->firstname = $data['firstname'];
        $client->middlename = $data['middlename'];
        $client->lastname = $data['lastname'];
        $client->date_of_birth = $data['date_of_birth'];
        $client->mobile_number = $data['mobile_number'];
        $client->email = $data['email'];
        $client->address = $data['address'];

        $status = false;
        if($client->save()){
            $status = true;
        }

        return $status;
    }

    public function appointmentSales($data)
    {
        $appointment = Appointment::findOrFail($data->appointment_id);
        $appointment_batch = $appointment->batch;

        $client_data = [
            'firstname' => $data->firstname,
            'middlename' => $data->middlename,
            'lastname' => $data->lastname,
            'date_of_birth' => $data->date_of_birth,
            'mobile_number' => $data->mobile_number,
            'email' => $data->email,
            'address' => $data->address,
        ];
        
        $client_info = $this->updateClientInfo($data->client_id, $client_data);
        
        $sales_data = [
            'payment_status' => 'pending',
            'user_id' => auth()->user()->id,
            'appointment_batch' => $appointment_batch,
            'guest_payment' => $data->total_price,
            'spa_id' => $data->spa_id
        ];

        $get_sales = Sale::where([
            'appointment_batch' => $appointment_batch,
            'spa_id' => $data->spa_id
        ])->first();

        if (!empty($get_sales)) {
            $salesStatus = $this->updateSales($get_sales->id,$sales_data);
        } else {
            $salesStatus = $this->createSales($sales_data);
        }

        $transaction_data = [
            'spa_id' => $data->spa_id,
            'service_id' => $data->value_services,
            'service_name' => $data->value_services_name,
            'amount' => $data->total_price,
            'therapist_1' => $data->therapist_1,
            'therapist_2' => $data->therapist_2,
            'client_id' => $data->client_id,
            'start_time' => $data->start_time,
            'end_time' => '',
            'plus_time' => $data->value_plus_time,
            'discount_rate' => '',
            'discount_amount' =>'',
            'tip' => '',
            'rating' => 0,
            'sales_type' => $data->appointment_type,
            'sales_id' => $salesStatus,
            'room_id' => $data->room_id,
        ];

        $transactionStatus = false;
        if ($salesStatus) {
            $transactionStatus = $this->createTransaction($transaction_data);
        }

        if ($transactionStatus) {
            $appointment->appointment_status = 'sales';
            $appointment->sales_id = $salesStatus;
        }

        $sataus = false;
        $message = 'Appointment could not be moved to sales. Please try again';
        if($appointment->save()){
            $status = true;
            $message = 'Appointment has been successfully moved to sales.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function createSales($data)
    {
        $sale = Sale::create([
            'spa_id' => $data['spa_id'],
            'amount_paid' => $data['guest_payment'],
            'payment_status' => $data['payment_status'],
            'user_id' => $data['user_id'],
            'appointment_batch' => $data['appointment_batch']
        ]);

        return $sale->id;
    }

    public function updateSales($id, $data)
    {
        $sale = Sale::findOrFail($id);
        $sale->amount_paid = $sale->amount_paid + $data['guest_payment'];

        $status = false;
        if($sale->save()){
            $status = true;
        }

        return $id;
    }

    public function createTransaction($data)
    {
        $data_array = [$data['therapist_1']];
        if (!empty($data['therapist_2'])) {
            $data_array = [$data['therapist_1'], $data['therapist_2']];
        }

        foreach ($data_array as $key => $data_arrays) {
            $therapist = $data['therapist_1'];
            $amount = $data['amount'];
            if ($key == 1) {
                $therapist = $data['therapist_2'];
                $amount = 0;
            }

            $start_time_val = date('Y-m-d H:i:s', strtotime($data['start_time']));
            $transaction = Transaction::create([
                'spa_id' => $data['spa_id'],
                'service_id' => $data['service_id'],
                'service_name' => $data['service_name'],
                'amount' => $amount,
                'therapist_1' => $therapist,
                'client_id' => $data['client_id'],
                'start_time' => $start_time_val,
                'end_time' => $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']),
                'plus_time' => $data['plus_time'],
                'discount_rate' => $data['discount_rate'],
                'discount_amount' => $data['discount_amount'],
                'tip' => $data['tip'],
                'rating' => $data['rating'],
                'sales_type' => $data['sales_type'],
                'sales_id' => $data['sales_id'],
                'room_id' => $data['room_id']
            ]);
        }

        return true;
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

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);

        $status = false;
        $message = 'Appointment could not be deleted. Please try again.';
        if ($appointment->delete()) {
            $status = true;
            $message = 'Appointment has been successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function getUpcomingGuest($id)
    {
        $appointment = Appointment::where(['spa_id' => $id, 'appointment_status' => 'reserved'])->with('client')->get();

        $data = [];
        foreach ($appointment as $list) {
            $created_at = Carbon::parse($list->created_at)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
            $seconds = strtotime($list->start_time) - strtotime($created_at);
            $total_minutes_in_seconds = $seconds;

            $data [] = [
                'id' => $list->id,
                'created_at' => $list->created_at,
                'start_time' => $list->start_time,
                'fullname' => $list->client->firstname.' '.$list->client->lastname,
                'mobile_number' => $list->client->mobile_number,
                'total_seconds' => $total_minutes_in_seconds
            ];
        }

        return $data;
    }
}
