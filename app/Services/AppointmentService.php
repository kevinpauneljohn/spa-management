<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Spa;
use App\Models\Owner;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\ClientService;
use App\Services\TransactionService;
use App\Services\SaleService;
// use App\Action\AppointmentAction;

class AppointmentService
{
    // private $appointmentAction;
    // public function __construct(AppointmentAction $appointmentAction)
    // {
    //     $this->appointmentAction = $appointmentAction;
    // }
    private $clientService;
    private $transactionService;
    private $saleService;

    public function __construct(
        ClientService $clientService,
        TransactionService $transactionService,
        SaleService $saleService
    ) {
        $this->clientService = $clientService;
        $this->transactionService = $transactionService;
        $this->saleService = $saleService;
    }

    public function create($data, $spa_id)
    {
        $code = 201;
        $status = false;
        $message = 'Unable to save appointments. Please try again.';

        $batch = Appointment::select(['batch'])->where([
            'spa_id' => $spa_id
        ])->orderBy('batch', 'DESC')->first();

        $batchNumber = 1;
        if ($batch) {
            $batchNumber = $batch['batch'] + 1;
        }

        DB::beginTransaction();
        try {
            foreach ($data->value as $key => $list) {
                $start_time = '';
                if (!empty($list['start_time'])) {
                    $start_time = date('Y-m-d H:i:s', strtotime($list['start_time']));
                }

                $primary = 'no';
                if ($key == 0) {
                    $primary = 'yes';
                }

                $client_id = 0;
                $client_owner_id = 0;
                $check_client = false;
                if (!empty($list['firstname']) && !empty($list['lastname'] && !empty($list['mobile_number']))) {
                    $check_client = $this->checkClient($list);
                }

                if ($check_client['status'] && $check_client['owner_client']) {
                    $client_id = $check_client['data']['id'];
                    $client_name = $check_client['data']['firstname'].' '.$check_client['data']['lastname'];

                    $client = $this->clientUpdate($client_id, $list);
                    $check_appointment = $this->checkInAppointment($client_id, $spa_id);
                    if ($client) {
                        if ($check_appointment < 1) {
                            $appointment = $this->appointmentCreate($spa_id, $client_id, $list, $batchNumber, $start_time, $primary);

                            if (!$appointment) {
                                throw new \Exception('Unable to save appointment. Please try again.');
                            }
                        } else {
                            throw new \Exception('Client "'.$client_name.'" already in appointment. Please try again.');
                        }
                    }
                } else {
                    if (!empty($list['existing_user_id'])) {
                        $client_id = $list['existing_user_id'];
                    }
                    $client_name = $list['firstname'].' '.$list['lastname'];

                    $client = $this->clientCreate($list);
                    $check_appointment = $this->checkInAppointment($client['data']['id'], $spa_id);
                    if ($client) {
                        if ($check_appointment < 1) {
                            $appointment = $this->appointmentCreate($spa_id, $client['data']['id'], $list, $batchNumber, $start_time, $primary);

                            if (!$appointment) {
                                throw new \Exception('Unable to save appointment. Please try again.');
                            }
                        } else {
                            throw new \Exception('Client "'.$client_name.'" already in appointment. Please try again.');
                        }
                    }
                }
            }

            $status = true;
            $message = 'Appointments has been successfully saved.';

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $code = 500;
            $status = false;
            $message = $e->getMessage();
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'code' => $code
        ];

        return $response;
    }

    public function data($id)
    {
        $appointment = Appointment::where(['spa_id' => $id, 'appointment_status' => 'reserved'])->with(['client'])->get();

        return DataTables::of($appointment)
            ->editColumn('client',function($appointment){
                $names = '';
                if (!empty($appointment->client_id)) {
                    $names .= ucfirst($appointment->client->firstname).' '.ucfirst($appointment->client->lastname);
                } else {
                    $names .= '<small><b>Care of "'.$this->getPrimaryAppointmentName($appointment->spa_id, $appointment->batch).'"</b></small>';
                }

                return $names;
            })
            ->editColumn('service',function ($appointment){

                return '<span class="badge bg-primary">'.$appointment->service_name.'</span>';
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
                return '<span class="badge bg-danger">'.ucfirst($appointment->appointment_status).'</span>';
            })
            ->editColumn('date',function ($appointment){
                $createdAt = Carbon::parse($appointment->created_at);
                $created_at = $createdAt->setTimezone('Asia/Manila')->format('F d, Y h:i A');

                return $created_at;
            })
            ->addColumn('action', function($appointment){
                $batch_id = $appointment->batch;

                $is_client_id = false;
                if (!empty($appointment->client_id)) {
                    $is_client_id = true;
                }

                $action = "";
                if (auth()->user()->can('view sales') || auth()->user()->hasRole('owner')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-warning view-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-eye"></i></a>&nbsp;';
                }

                if (auth()->user()->can('edit sales') || auth()->user()->hasRole('owner')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-primary edit-appointment-btn" id="'.$appointment->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }

                if (auth()->user()->can('move sales') || auth()->user()->hasRole('owner')) {
                    $action .= '<a href="#" data-date="'.$appointment->start_time.'" data-name="'.$is_client_id.'" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-success move-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-exchange-alt"></i></a>&nbsp;';
                }

                if (auth()->user()->can('delete sales') || auth()->user()->hasRole('owner')) {
                    $action .= '<a href="#" data-batch="'.$batch_id.'" class="btn btn-sm btn-outline-danger delete-appointment-btn" id="'.$appointment->id.'"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }

                return $action;
            })
            ->rawColumns(['action','client','service','batch','amount','type','status','date'])
            ->make(true);
    }

    public function getPrimaryAppointmentName($spa_id, $batch)
    {
        $appointment = Appointment::where([
            'spa_id' => $spa_id,
            'batch' => $batch,
            'primary' => 'yes'
        ])->with(['client'])->first();

        $data = '';
        if (!empty($appointment)) {
            $data = ucfirst($appointment->client->firstname).' '.ucfirst($appointment->client->lastname);
        }

        return $data;
    }

    // Enhanced Create Query for Walk In reserved Now with try catch error and DB: Transaction function
    public function appointmentCreateSales($data, $id, $amount)
    {
        $code = 201;
        $status = false;
        $message = 'Unable to save appointments. Please try again.';
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            $spa_id = $id;
            $user_id = auth()->user()->id;
            $payment_status = 'pending';

            $sale_data = [
                'spa_id' => $spa_id,
                'amount_paid' => $amount,
                'payment_status' => $payment_status,
                'user_id' => $user_id,
                'appointment_batch' => 0,
                'payment_method' => NULL,
                'payment_account_number' => NULL,
                'payment_bank_name' => NULL
            ];

            $sale = $this->createSale($sale_data);

            foreach ($data->value as $list) {
                $client_id = 0;
                $check_client = $this->checkClient($list);

                if ($check_client['status']) {
                    $client_id = $check_client['data']['id'];
                    $client_name = $check_client['data']['firstname'].' '.$check_client['data']['lastname'];

                    $check_transaction = $this->checkInTransaction($client_id, $spa_id, $now);
                    $check_appointment = $this->checkInAppointment($client_id, $spa_id);

                    $client = $this->clientUpdate($client_id, $list);
                    if (!$check_transaction['status']) {
                        if ($client) {
                            $transaction = $this->transactionCreate($spa_id, $client_id, $sale['data']['id'], $list);

                            if (!$transaction) {
                                throw new \Exception('Unable to save transaction. Please try again.');
                            }
                        }
                    } else if ($check_appointment < 1) {
                        if ($client) {
                            $transaction = $this->transactionCreate($spa_id, $client_id, $sale['data']['id'], $list);

                            if (!$transaction) {
                                throw new \Exception('Unable to save transaction. Please try again.');
                            }
                        }
                    } else {
                        throw new \Exception('Client "'.$client_name.'" already in transaction or appointment. Please try again.');
                    }
                } else {
                    if (!empty($list['existing_user_id'])) {
                        $client_id = $list['existing_user_id'];
                    }
                    $client_name = $list['firstname'].' '.$list['lastname'];

                    $check_transaction = $this->checkInTransaction($client_id, $spa_id, $now);
                    $check_appointment = $this->checkInAppointment($client_id, $spa_id);

                    $client = $this->clientCreate($list);
                    if (!$check_transaction['status']) {
                        if ($client) {
                            $transaction = $this->transactionCreate($spa_id, $client['data']['id'], $sale['data']['id'], $list);

                            if (!$transaction) {
                                throw new \Exception('Unable to save transaction. Please try again.');
                            }
                        }
                    } else if ($check_appointment < 1) {
                        if ($client) {
                            $transaction = $this->transactionCreate($spa_id, $client['data']['id'], $sale['data']['id'], $list);

                            if (!$transaction) {
                                throw new \Exception('Unable to save transaction. Please try again.');
                            }
                        }
                    } else {
                        throw new \Exception('Client "'.$client_name.'" already in transaction or appointment. Please try again.');
                    }
                }
            }

            $status = true;
            $message = 'Sales Transaction and Client Information successfully saved.';

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $code = 500;
            $status = false;
            $message = $e->getMessage();
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'code' => $code
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
        $appointment->start_time_formatted = date('d F Y, h:i A', strtotime($appointment->start_time));

        $appointment->client_id = '';
        $appointment->firstname = '';
        $appointment->lastname = '';
        $appointment->middlename = '';
        $appointment->date_of_birth = '';
        $appointment->mobile_number = '';
        $appointment->email = '';
        $appointment->address = '';
        $appointment->client_type = 'new';
        $data = [];
        if ($appointment) {
            $data = [
                'id' => $appointment->id,
                'start_time' => $appointment->start_time_formatted,
                'appointment_type' => $appointment->appointment_type,
                'social_media_type' => $appointment->social_media_type,
                'client_id' => $appointment->client->id,
                'firstname' => ucfirst($appointment->client->firstname),
                'middlename' => ucfirst($appointment->client->middlename),
                'lastname' => ucfirst($appointment->client->lastname),
                'date_of_birth' => $appointment->client->date_of_birth,
                'mobile_number' => $appointment->client->mobile_number,
                'email' => $appointment->client->email,
                'address' => $appointment->client->address,
                'client_type' => $appointment->client->client_type,
                'start_time_formatted' => $appointment->start_time_formatted,
                'amount' => $appointment->amount,
                'start_time' => $appointment->start_time,
            ];
        }

        return $data;
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
            'client_type' => $data->client_type
        ];

        $status = false;
        $message = 'Unable to update Appointment. Please try again.';
        if ($appointment->save()) {
            if (!empty($data->client_id)) {
                $client_info = $this->updateClientInfo($data->client_id, $client_data);
            } else {
                $client_info = $this->saveClient(0, $client_data);

                $appointment->client_id = $client_info;
                $appointment->save();
            }

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
        foreach ($appointment as $key => $list) {
            $created_at = Carbon::parse($list->created_at)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
            $seconds = strtotime($list->start_time) - strtotime($created_at);
            $total_minutes_in_seconds = $seconds;

            $fullname = 'Batch # '.$list->batch. ' <small><b>Care of "'.$this->getPrimaryAppointmentName($list->spa_id, $list->batch).'"</b></small>';
            $mobile_number = '[N/A]';
            if (!empty($list->client_id)) {
                $fullname = 'Batch # '.$list->batch.' '.$list->client->firstname.' '.$list->client->lastname;
                $mobile_number = $list->client->mobile_number;
            }

            $data [] = [
                'id' => $list->id,
                'created_at' => $list->created_at,
                'start_time' => $list->start_time,
                'fullname' => $fullname,
                'mobile_number' => $mobile_number,
                'total_seconds' => $total_minutes_in_seconds
            ];
        }

        return $data;
    }

    public function getAppointmentResponses($id)
    {
        $spa = Spa::findOrFail($id);
        $rooms = range(1, $spa->number_of_rooms);

        $data = [
            'rooms' => $this->getRooms($rooms, $id),
        ];

        return $data;
    }

    private function getRooms($room, $id)
    {
        $data = [];
        foreach ($room as $list) {
            $data [] = $this->getData($list, $id);
        }

        return $data;
    }

    private function getData($room, $spa_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = Transaction::where(
            'room_id', $room
        )->where('spa_id', $spa_id)->where(
            'end_time', '>=', $now
        )->with(['client'])->first();

        $dataList = [];
        $isAvailable = true;
        $isColorSet = 'bg-info';
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
                    'firstname' => $transaction->client['firstname'],
                    'middlename' => $transaction->client['middlename'],
                    'lastname' => $transaction->client['lastname'],
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

    public function checkBatch($id, $batch)
    {
        $appointment = Appointment::where([
            'spa_id' => $id,
            'batch' => $batch,
            'appointment_status' => 'reserved'
        ])->with(['client'])->get()->count();

        $status = false;
        if ($appointment >= 1) {
            $status = true;
        }

        return $status;
    }

    //create new appointment
    public function appointmentCreate($spa_id, $client_id, $list, $batchNumber, $start_time, $primary)
    {
        $status = false;
        $appointment = Appointment::create([
            'spa_id' => $spa_id,
            'client_id' => $client_id,
            'service_id' => $list['service_id'],
            'service_name' => $list['service_name'],
            'batch' => $batchNumber,
            'amount' => $list['price'],
            'start_time' => $start_time,
            'appointment_type' => $list['appointment_type'],
            'social_media_type' => $list['social_type'],
            'appointment_status' => 'reserved',
            'primary' => $primary
        ]);

        if ($appointment) {
            $status = true;
        }

        return $status;
    }

    //Check for existing reserved appointment
    public function checkInAppointment($client_id, $spa_id)
    {
        $appointment = Appointment::where([
            'client_id' => $client_id,
            'spa_id' => $spa_id,
            'appointment_status' => 'reserved'
        ])->count();

        $count = 0;
        if ($appointment > 0) {
            $count = $appointment;
        }

        return $count;
    }

    // Create Sales Service
    public function createSale($data)
    {
        return $this->saleService->create($data);
    }

    // Check for active client service
    public function checkClient($data)
    {
        return $this->clientService->get_client($data);
    }

    // Create Client Service
    public function clientCreate($data)
    {
        return $this->clientService->create($data);
    }

    // Update Client Service
    public function clientUpdate($id, $data)
    {
        return $this->clientService->update($id, $data);
    }

    // Check for active transaction service
    public function checkInTransaction($client_id, $spa_id, $dateTime)
    {
        return $this->transactionService->get_transaction($client_id, $spa_id, $dateTime);
    }

    // Create Transaction Service
    public function transactionCreate($spa_id, $client_id, $sales_id, $transaction_data)
    {
        return $this->transactionService->create($spa_id, $client_id, $sales_id, $transaction_data);
    }
}
