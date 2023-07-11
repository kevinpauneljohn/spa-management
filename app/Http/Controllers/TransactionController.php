<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Therapist;
use App\Models\Client;
use App\Models\Owner;
use App\Models\User;
use App\Models\Sale;
use App\Models\SalesShift;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function lists($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $transaction = Transaction::where('spa_id', $id)
        ->where('amount','>', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        return DataTables::of($transaction)
            ->editColumn('client',function($transaction){
                return $transaction->client['firstname'].' '.$transaction->client['lastname'];
            })
            ->addColumn('service',function ($transaction){
                return '<span class="badge bg-primary">'.$transaction->service_name.'</span>';
            })
            ->addColumn('masseur',function ($transaction){
                $masseur = $this->getMasseurName(
                    $transaction->spa_id, 
                    $transaction->service_id, 
                    $transaction->client_id, 
                    $transaction->sales_id
                );

                return '<span class="badge bg-danger">'.$masseur.'</span>';
            })
            ->addColumn('start_time',function ($transaction){
                return date('F d, Y h:i:s A', strtotime($transaction->start_time));
            })
            ->addColumn('plus_time',function ($transaction){
                if ($transaction->plus_time > 0) {
                    $plus_time = $transaction->plus_time * 60;
                    $converted_plus_time = gmdate("H:i:s", $plus_time);
                    return CarbonInterval::createFromFormat('H:i:s', $converted_plus_time)
                        ->forHumans(CarbonInterface::DIFF_ABSOLUTE, true, 3);
                } else {
                    return 'None';
                }
            })
            ->addColumn('end_time',function ($transaction){
                return date('F d, Y h:i:s A', strtotime($transaction->end_time));
            })
            ->addColumn('room',function ($transaction){
                return $transaction->room_id;
            })
            ->addColumn('amount',function ($transaction){
                return '&#8369; '.$transaction->amount;
            })
            ->addColumn('status',function ($transaction){
                $dateNow = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
                $status = '<span class="badge bg-success">Done</span>';
                if ($transaction->end_time >= $dateNow) {
                    $status = '<span class="badge bg-primary">On-Going</span>';
                }

                return $status;
            })
            ->addColumn('action', function($transaction){
                $dateNow = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
                $date_start_time = date('H:i m/d/Y', strtotime($transaction->start_time));
                $action = "";

                if(auth()->user()->can('edit sales') || auth()->user()->hasRole('owner')) {
                    if ($transaction->end_time >= $dateNow  || auth()->user()->hasRole('owner')) {
                        $action .= '<a href="#" data-start_date="'.$date_start_time.'" data-end_date="'.$transaction->end_time.'" class="btn btn-xs btn-outline-primary rounded edit-sales-btn" id="'.$transaction->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                    }
                    $action .= '<a href="#" data-start_date="'.$date_start_time.'" data-end_date="'.$transaction->end_time.'" class="btn btn-xs btn-outline-warning rounded stop-sales-btn" id="'.$transaction->id.'"><i class="fas fa-ban"></i></a>&nbsp;';
                }

                // if(auth()->user()->can('delete sales') || auth()->user()->hasRole('owner')) {
                //     $action .= '<a href="#" class="btn btn-xs btn-outline-danger rounded delete-sales-btn" id="'.$transaction->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                // }

                return $action;
            })
            ->rawColumns(['action','client','service','masseur','amount','status'])
            ->make(true);
    }

    public function getMasseurName($spa_id, $service_id, $client_id, $sales_id)
    {
        $transaction = Transaction::where([
            'spa_id' => $spa_id,
            'service_id' => $service_id,
            'client_id' => $client_id,
            'sales_id' => $sales_id
        ])->get();

        $data = [];
        if (!empty($transaction)) {
            foreach ($transaction as $list) {
                $therapist = Therapist::with(['user'])->findOrFail($list->therapist_1);

                $data [] = $therapist->user['firstname'].' '.$therapist->user['lastname'];
            }
        }

        $dataName = '';
        if (!empty($data)) {
            $dataName = implode(",<br />", $data);
        }

        return $dataName;
    }

    public function getTotalSales($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $transaction = Transaction::where('spa_id', $id)
            ->where('amount','>', 0)
            ->where('end_time', '>=', $now)
            ->with(['client'])->get();


        $countTransaction = $transaction->count();

        return $countTransaction;
    }

    public function getRoomAvailability($id)
    {
        $spa = Spa::findOrFail($id);

        $rooms = range(1, $spa->number_of_rooms);

        $data = [];
        foreach ($rooms as $room) {
            $data [] = $this->getData($room, $spa_id);
        }
    }

    public function getTherapistAvailability($id)
    {
        $therapist = Therapist::where([
            'spa_id' => $id,
        ])->with(['user'])->get();

        $data = [];
        if (!empty($therapist)) {
            foreach ($therapist as $list) {
                $data [$list->user['firstname']] = [
                    'id' => $list->id,
                    'firstname' => $list->user['firstname'],
                    'lastname' => $list->user['lastname'],
                    'data' => $this->getTherapistTransaction($id, $list->id)
                ];
            }
        }

        return $data;
    }

    public function getTherapistTransaction($spa_id, $therapist_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = transaction::where(function ($query) use ($therapist_id) {
            $query->where('therapist_1', $therapist_id)
                  ->orWhere('therapist_2', $therapist_id);
        })->where('spa_id', $spa_id)->where('end_time', '>=', $now)->with(['service'])->first();

        $data = [];
        if (!empty($transaction)) {
            $plus_time = 0;
            if (!empty($transaction->plus_time)) {
                $plus_time = $transaction->plus_time;
            }

            $seconds = strtotime($transaction->end_time) - strtotime($now);
            $total_minutes = $transaction->service['duration'] + $plus_time;
            $total_minutes_in_seconds = $total_minutes * 60;
            // $data = $transaction;
            $data = [
                'id' => $transaction->id,
                'end_time' => $transaction->end_time,
                'start_time' => $transaction->start_time,
                'milliseconds' => $seconds * 1000,
                'seconds' => $seconds,
                'total_seconds' => $total_minutes_in_seconds,
                'room_id' => $transaction->room_id
            ];
        }

        return $data;
    }

    public function show($id)
    {
        return $this->transactionService->view($id);
        // $data = [];
        // $transaction = Transaction::where('id', $id)->with(['client', 'therapist', 'therapist2', 'service'])->first();

        // if (!empty($transaction)) {
        //     $spa_id = $transaction->spa_id;
        //     $service_id = $transaction->service_id;
        //     $client_id = $transaction->client_id;
        //     $sales_id = $transaction->sales_id;

        //     $getTranscations = Transaction::where([
        //         'spa_id' => $spa_id,
        //         'service_id' => $service_id,
        //         'client_id' => $client_id,
        //         'sales_id' => $sales_id,
        //     ])->orderBy('amount', 'DESC')->with(['client'])->get();

        //     if ($getTranscations->count() > 1) {
        //         $therapist_2_id = $getTranscations[1]->id;
        //         $therapist_2 = $getTranscations[1]->therapist_1;
        //         $therapist_2_name = $this->getTherapistName($getTranscations[1]->therapist_1);
        //     } else {
        //         $therapist_2_id = '';
        //         $therapist_2 = '';
        //         $therapist_2_name = '';
        //     }

        //     $getAmount = $getTranscations[0]->amount;
        //     $getId = $getTranscations[0]->id;
        //     if ($getTranscations[0]->amount == 0) {
        //         $getAmount = $getTranscations[1]->amount;
        //         $getId = $getTranscations[1]->id;
        //     }

        //     $amount_formatted = number_format($getAmount);
        //     $start_time = date('d F Y h:i A', strtotime($getTranscations[0]->start_time));
        //     $start_date_formatted = date('h:i:s A', strtotime($getTranscations[0]->start_time));
        //     $start_time_formatted = date('Y-m-d H:i:s', strtotime($getTranscations[0]->start_time));
        //     $end_time_formatted = date('h:i:s A', strtotime($getTranscations[0]->end_time));
        //     $birth_date_formatted = '';
        //     if (!empty($getTranscations[0]->client['date_of_birth'])) {
        //         $birth_date_formatted = date('F d, Y', strtotime($getTranscations[0]->client['date_of_birth']));
        //     }

        //     $plus_time_formatted = 'None';
        //     if ($getTranscations[0]->plus_time > 0) {
        //         $plus_time = $transaction->plus_time * 60;
        //         $converted_plus_time = gmdate("H:i:s", $plus_time);
        //         $plus_time_formatted =  CarbonInterval::createFromFormat('H:i:s', $converted_plus_time)
        //             ->forHumans(CarbonInterface::DIFF_ABSOLUTE, true, 3);
        //     }

        //     $data = [
        //         'id' => $getId,
        //         'firstname' => $getTranscations[0]->client['firstname'],
        //         'middlename' => $getTranscations[0]->client['middlename'],
        //         'lastname' => $getTranscations[0]->client['lastname'],
        //         'date_of_birth' => $getTranscations[0]->client['date_of_birth'],
        //         'date_of_birth_formatted' => $birth_date_formatted,
        //         'mobile_number' => $getTranscations[0]->client['mobile_number'],
        //         'email' => $getTranscations[0]->client['email'],
        //         'address' => $getTranscations[0]->client['address'],
        //         'client_type' => $getTranscations[0]->client['client_type'],
        //         'service_id' => $getTranscations[0]->service_id,
        //         'service_name' => $getTranscations[0]->service_name,
        //         'therapist_1' => $getTranscations[0]->therapist_1,
        //         'therapist_1_name' => $this->getTherapistName($getTranscations[0]->therapist_1),
        //         'start_time' => $getTranscations[0]->start_time,     
        //         'start_and_end_time' => $start_date_formatted.' to '.$end_time_formatted,  
        //         'start_date_formatted' => $start_date_formatted,
        //         'start_time_formatted' => $start_time_formatted,
        //         'end_time' => $getTranscations[0]->end_time,
        //         'end_date_formatted' => $end_time_formatted,
        //         'plus_time' => $getTranscations[0]->plus_time,  
        //         'plus_time_formatted' => $plus_time_formatted,
        //         'room_id' => $getTranscations[0]->room_id, 
        //         'amount' => $getAmount, 
        //         'client_id' => $getTranscations[0]->client_id, 
        //         'sales_id' => $getTranscations[0]->sales_id, 
        //         'amount_formatted' => $amount_formatted, 
        //         'therapist_2_id' => $therapist_2_id,
        //         'therapist_2' => $therapist_2,
        //         'therapist_2_name' => $therapist_2_name,
        //     ];
        // }

        // return $transaction;
    }

    public function getTherapistName($id)
    {
        $therapist = Therapist::findOrFail($id);

        return $therapist->user->firstname.' '.$therapist->user->lastname;
    }

    public function getData($id)
    {
        $user_id = auth()->user()->id;
        $date_today = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d');
        $todays_from = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d 00:00:00');
        $todays_to = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d 23:59:59');

        $months_from = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-01 00:00:00');
        $months_to = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-t 23:59:59');

        $dailyTransaction = Transaction::where([
            'spa_id' => $id,
        ])->whereDate('start_time', '>=', $todays_from)->whereDate('end_time', '<=', $todays_to)->get();

        $dailyTransactionCount = 0;
        $dailyClient = [];
        if ($dailyTransaction->count() > 0) {
            $dailyTransactionCount = $dailyTransaction->count();
            foreach ($dailyTransaction as $dailyTransactions) {
                $dailyClient [] = $dailyTransactions->client_id;
            }

            $dailyClient = array_unique($dailyClient);
        }

        $monthlyTransaction = Transaction::where([
            'spa_id' => $id,
        ])->whereDate('start_time', '>=', $months_from)->whereDate('end_time', '<=', $months_to)->get();

        $monthlyTransactionCount = 0;
        $monthlyClient = [];
        if ($monthlyTransaction->count() > 0) {
            $monthlyTransactionCount = $monthlyTransaction->count();
            foreach ($monthlyTransaction as $monthlyTransactions) {
                $monthlyClient [] = $monthlyTransactions->client_id;
            }

            $monthlyClient = array_unique($monthlyClient);
        }

        $newClient = Client::whereDate(
            'created_at', '>=', $months_from
        )->whereDate(
            'created_at', '<=', $months_to
        )->whereIn('id', $monthlyClient)->get()->count();

        $allClientsTransactions = Transaction::where('spa_id', $id,)->groupBy('client_id')->pluck('client_id');
        $allClients = Client::whereIn('id', $allClientsTransactions)->get()->count();

        $saleShift = SalesShift::where([
            'user_id' => auth()->user()->id, 
            'spa_id' => $id,
            'confirm_start_shift' => 'yes',
            ])->orderBy('id', 'desc')->first();

        $salesCondition = [];
        if (auth()->user()->hasRole('front desk')) {
            if (!empty($saleShift)) {
                if (!empty($saleShift->end_shift)) {
                    $salesCondition = [$saleShift->start_shift, $saleShift->end_shift];
                } else {
                    $salesCondition = [$saleShift->start_shift, $todays_to]; 
                }
                
            }
        } else {
            $salesCondition = [$todays_from, $todays_to];
        }

        $sale = Sale::where([
            'user_id' => auth()->user()->id, 
            'payment_status' => 'paid'
        ])->whereBetween('paid_at', $salesCondition)->get();
        
        $total_sale = 0;
        if (!empty($sale)) {
            foreach ($sale as $sales) {
                $total_sale+= $sales->amount_paid;
            }
        }

        if (auth()->user()->hasRole('front desk')) {
            $total_sale += $saleShift->start_money;    
        }
        
        $response = [
            'daily_appointment'   => $dailyTransactionCount,
            'monthly_appointment'   => $monthlyTransactionCount,
            'new_clients' => $newClient,
            'total_sales' => '&#8369;'.number_format($total_sale, 2, '.', ','),
            'sales' => $total_sale,
        ]; 

        return $response;
    }

    public function getInvoice($id)
    {
        // $transaction = Transaction::with(['client', 'service', 'spa'])->findOrFail($id);
        // $owner = Owner::findOrFail($transaction->spa['owner_id']);
        // $sales = Sale::findOrFail($transaction->sales_id);
        // $sales_id = substr($sales->id, -12);
        // $response = [
        //     'info'   => $transaction,
        //     'owner'   => User::findOrFail($owner->user_id),
        //     'sales' => $sales_id,
        // ]; 

        // return $response;

        $sales = Sale::with(['spa'])->findOrFail($id);
        $transaction = Transaction::where('sales_id', $id)->where('amount','>', 0)->with(['client', 'service'])->get();
        $owner = Owner::findOrFail($sales->spa['owner_id']);
        // $sales = Sale::findOrFail($transaction->sales_id);
        // $sales_id = substr($sales->id, -12);
        // $response = [
        //     'info'   => $transaction,
        //     'owner'   => User::findOrFail($owner->user_id),
        //     'sales' => $sales_id,
        // ]; 

        // return $response;

        $response = [
            'transactions'   => $transaction,
            'owner'   => User::findOrFail($owner->user_id),
            'sales' => $sales,
            'invoice' => substr($sales->id, -6)
        ]; 

        return $response;
    }

    public function stopTransaction($id)
    {
        return $this->transactionService->stopTransactions($id);
    }

    public function update(Request $request, $id)
    {
        return $this->transactionService->update($request, $id);
    }

    public function preparation_time()
    {
        return $this->transactionService->preparation_time();
    }
}
