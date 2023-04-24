<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Therapist;

class TransactionController extends Controller
{
    public function lists($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $transaction = Transaction::where('spa_id', $id)
            ->where('amount','>', 0)
            ->where('end_time', '>=', $now)
            ->with(['client'])->get();

        return DataTables::of($transaction)
            ->editColumn('client',function($transaction){
                return $transaction->client['firstname'].' '.$transaction->client['lastname'];
            })
            ->addColumn('service',function ($transaction){
                return $transaction->service_name;
            })
            ->addColumn('masseur',function ($transaction){
                $masseur = $this->getMasseurName(
                    $transaction->spa_id, 
                    $transaction->service_id, 
                    $transaction->client_id, 
                    $transaction->sales_id
                );

                return $masseur;
            })
            ->addColumn('start_time',function ($transaction){
                return date('F d, Y h:i:s A', strtotime($transaction->start_time));
            })
            ->addColumn('plus_time',function ($transaction){
                $plus_time = $transaction->plus_time * 60;
                $converted_plus_time = gmdate("H:i:s", $plus_time);

                return $converted_plus_time;
            })
            ->addColumn('end_time',function ($transaction){
                return date('F d, Y h:i:s A', strtotime($transaction->end_time));
            })
            ->addColumn('room',function ($transaction){
                return $transaction->room_id;
            })
            ->addColumn('Amount',function ($transaction){
                return $transaction->amount;
            })
            ->addColumn('action', function($transaction){
                $date_start_time = date('H:i m/d/Y', strtotime($transaction->start_time));
                $action = "";
                if(auth()->user()->can('edit sales')) {
                    $action .= '<a href="#" data-start_date="'.$date_start_time.'" class="btn btn-xs btn-outline-primary rounded edit-sales-btn" id="'.$transaction->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }

                if(auth()->user()->can('delete sales')) {
                    $action .= '<a href="#" class="btn btn-xs btn-outline-danger rounded delete-sales-btn" id="'.$transaction->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                
                return $action;
            })
            ->rawColumns(['action','client', 'masseur'])
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
                $therapist = Therapist::findOrFail($list->therapist_1);

                $data [] = $therapist->firstname.' '.$therapist->lastname;
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
        ])->get();

        $data = [];
        if (!empty($therapist)) {
            foreach ($therapist as $list) {
                $data [$list->firstname] = [
                    'id' => $list->id,
                    'firstname' => $list->firstname,
                    'lastname' => $list->lastname,
                    'data' => $this->getTherapistTransaction($id, $list->id)
                ];
            }
        }

        return $data;
    }

    public function getTherapistTransaction($spa_id, $therapist_id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = Transaction::where([
            'spa_id' => $spa_id,
            'therapist_1' => $therapist_id,
        ])->where('end_time', '>=', $now)->first();

        $data = [];
        if (!empty($transaction)) {
            $data = $transaction;
        }

        return $data;
    }

    public function getLatestReservation($id)
    {
        $firstDay = date('Y-m-01 00:00:00');
        $lastDay = date('Y-m-t 23:59:59');

        $transaction = Transaction::where(
            'spa_id', $id
        )->where(
            'amount','>', 0
        )->where(
            'start_time', '>=', $firstDay
        )->where(
            'end_time', '<=', $lastDay
        )->with(['client'])->get();

        return DataTables::of($transaction)
            ->editColumn('client',function($transaction){
                return $transaction->client['firstname'].' '.$transaction->client['lastname'];
            })
            ->addColumn('service',function ($transaction){
                return $transaction->service_name;
            })
            ->addColumn('room',function ($transaction){
                return $transaction->room_id;
            })
            ->addColumn('Amount',function ($transaction){
                return $transaction->amount;
            })
            ->addColumn('date',function ($transaction){
                return date('F d, Y H:i:s A', strtotime($transaction->created_at));
            })
            ->rawColumns(['client', 'date'])
            ->make(true);
    }

    public function show($id)
    {
        $data = [];
        $transaction = Transaction::where('id', $id)->first();

        if (!empty($transaction)) {
            $spa_id = $transaction->spa_id;
            $service_id = $transaction->service_id;
            $client_id = $transaction->client_id;
            $sales_id = $transaction->sales_id;

            $getTranscations = Transaction::where([
                'spa_id' => $spa_id,
                'service_id' => $service_id,
                'client_id' => $client_id,
                'sales_id' => $sales_id,
            ])->orderBy('amount', 'DESC')->with(['client'])->get();

            if ($getTranscations->count() > 1) {
                $therapist_2_id = $getTranscations[1]->id;
                $therapist_2 = $getTranscations[1]->therapist_1;
            } else {
                $therapist_2_id = '';
                $therapist_2 = '';
            }

            $getAmount = $getTranscations[0]->amount;
            $getId = $getTranscations[0]->id;
            if ($getTranscations[0]->amount == 0) {
                $getAmount = $getTranscations[1]->amount;
                $getId = $getTranscations[1]->id;
            }

            $amount_formatted = number_format($getAmount);
            $start_time = date('H:i m/d/Y', strtotime($getTranscations[0]->start_time));
            $data = [
                'id' => $getId,
                'firstname' => $getTranscations[0]->client['firstname'],
                'middlename' => $getTranscations[0]->client['middlename'],
                'lastname' => $getTranscations[0]->client['lastname'],
                'date_of_birth' => $getTranscations[0]->client['date_of_birth'],
                'mobile_number' => $getTranscations[0]->client['mobile_number'],
                'email' => $getTranscations[0]->client['email'],
                'address' => $getTranscations[0]->client['address'],
                'client_type' => $getTranscations[0]->client['client_type'],
                'service_id' => $getTranscations[0]->service_id,
                'service_name' => $getTranscations[0]->service_name,
                'therapist_1' => $getTranscations[0]->therapist_1,
                'start_time' => $start_time,     
                'plus_time' => $getTranscations[0]->plus_time,  
                'room_id' => $getTranscations[0]->room_id, 
                'amount' => $getAmount, 
                'client_id' => $getTranscations[0]->client_id, 
                'sales_id' => $getTranscations[0]->sales_id, 
                'amount_formatted' => $amount_formatted, 
                'therapist_2_id' => $therapist_2_id,
                'therapist_2' => $therapist_2,     
            ];
        }

        return $data;
    }
}
