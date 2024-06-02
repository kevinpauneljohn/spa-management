<?php

namespace App\Services;
use App\Models\Discount;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Therapist;
use App\Models\Sale;
use App\Models\Client;
use Carbon\Carbon;
use App\Services\RoomService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionService
{
    private $roomService;

    public function __construct(
        RoomService $roomService
    ) {
        $this->roomService = $roomService;
    }

    public function get_transaction($client_id, $spa_id, $dateTime)
    {
        $date = date('Y-m-d H:i:s', strtotime($dateTime));
        $transaction = Transaction::where('client_id', $client_id)
            ->where('spa_id', $spa_id)
            ->where('start_time', '<=', $date)
            ->where('end_time', '>=', $date)
            ->first();

        $status = false;
        $data = [];
        if ($transaction) {
            $status = true;
            $data = $transaction;
        }

        return [
            'status' => $status,
            'data' => $data,
        ];
    }

    public function create($spa_id, $client_id, $sales_id, $data)
    {
        $status = false;
        $primary = 'no';
        if ($data['key'] == 1) {
            $primary = 'yes';
        }

        $start_time_val = date('Y-m-d H:i:s', strtotime($data['start_time']));
        $transaction = Transaction::create([
            'spa_id' => $spa_id,
            'service_id' => $data['service_id'],
            'service_name' => $data['service_name'],
            'amount' => $data['price'],
            'therapist_1' => $data['therapist_1'],
            'therapist_2' => $data['therapist_2'],
            'client_id' => $client_id,
            'preparation_time' => $data['preparation_time'],
            'start_time' => $start_time_val,
            'end_time' => $this->getEndTime($data['service_id'], $start_time_val, $data['plus_time']),
            'plus_time' => $data['plus_time'],
            'discount_rate' => NULL,
            'discount_amount' => NULL,
            'tip' => NULL,
            'rating' => 0,
            'sales_type' => $data['appointment_type'],
            'sales_id' => $sales_id,
            'room_id' => $data['room_id'],
            'primary' => $primary,
        ]);

        if ($transaction) {
            $status = true;
        }
        return $status;
    }

    public function update($request, $id)
    {
        $status = false;
        $message = 'Something went wrong. Unable to save the transaction.';
        DB::beginTransaction();
        try {
            $sales = Sale::findOrFail($request->sales_id);
            $sales->amount_paid = ($sales->amount_paid + $request->amount) - $request->prevAmount;

            if ($sales->save()) {
                $transaction = Transaction::findOrFail($id);
                $transaction->service_id = $request->service_id;
                $transaction->service_name = $request->service_name;
                $transaction->amount = $request->amount;
                $transaction->therapist_1 = $request->therapist_1;
                $transaction->therapist_2 = $request->therapist_2;
                $transaction->plus_time = $request->plus_time;
                $transaction->room_id = $request->room_id;
                if (!empty($request->plus_time)) {
                    $transaction->end_time = $this->getEndTime($request->service_id, $transaction->start_time, $request->plus_time);
                }

                if ($transaction->save()) {
                    $client = Client::findOrFail($transaction->client_id);
                    $client->mobile_number = $request->mobile_number;
                    $client->email = $request->email;
                    $client->address = $request->address;
                    $client->client_type = $request->client_type;
                    if ($client->save()) {
                        $status = true;
                        $message = 'Transaction successfully updated.';
                        DB::commit();
                    } else {
                        DB::rollback();
                    }
                } else {
                    DB::rollback();
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ];

        return $response;
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
        $yesterday_start = Carbon::now()->setTimezone('Asia/Manila')->subDays(1)->format('Y-m-d 00:00:01');
        $yesterday_end = Carbon::now()->setTimezone('Asia/Manila')->subDays(1)->format('Y-m-d 23:59:59');
        $transaction = Transaction::where('therapist_1', $therapist_id)
            ->where('amount', '>', 0)
            ->where('start_time', '>=', $yesterday_start)
            ->where('end_time', '<=', $yesterday_end)
            ->count();

        return $transaction;
    }

    public function therapistAvailability($spa_id, $therapist_id, $dateTime)
    {
        $transaction = transaction::where(function ($query) use ($therapist_id) {
            $query->where('therapist_1', $therapist_id)
                  ->orWhere('therapist_2', $therapist_id);
        })->where(
            'spa_id', $spa_id
        )->where(
            'start_time', '<=', $dateTime
        )->where(
            'end_time', '>=', $dateTime
        )->first();

        $status = true;
        $data = [];
        if ($transaction) {
            $status = false;
        }

        return $status;
    }

    public function stopTransactions($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = Transaction::findOrFail($id);
        $transaction->end_time = $now;

        $code = 422;
        $status = false;
        $message = 'Client transaction could not be stop. Please try again.';
        if ($transaction->save()) {
            $multi_transaction = Transaction::where([
                'spa_id' => $transaction->spa_id,
                'client_id' => $transaction->client_id,
                'start_time' => $transaction->start_time,
                'service_id' => $transaction->service_id,
                'amount' => 0,
                'sales_id' => $transaction->sales_id
            ])->first();

            if (!empty($multi_transaction)) {
                $multi_transaction->end_time = $now;
                $multi_transaction->save();
            }

            $code = 200;
            $status = true;
            $message = 'Client Transaction successfully stopped.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ];

        return response($response, $code);
    }

    public function view($id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $transaction = Transaction::with(['client', 'therapist', 'therapist2', 'service'])->findOrFail($id);

        $transaction->client->firstname = ucwords($transaction->client->firstname);
        $transaction->client->middlename = ucwords($transaction->client->lastname) ? ucwords($transaction->client->middlename) : '';
        $transaction->client->lastname = ucwords($transaction->client->lastname);
        $transaction->client->date_of_birth_formatted = $transaction->client->date_of_birth ? date('F d, Y', strtotime($transaction->client->date_of_birth)) : '';
        $transaction->amount_formatted = number_format($transaction->amount, 2);
        $transaction->therapist_1_name = $transaction->therapist->user->fullname;
        $transaction->therapist_2_name = $transaction->therapist_2 ? $transaction->therapist2->user->fullname : '';
        $transaction->start_time_formatted = $transaction->start_time ? date('F d, Y h:i A', strtotime($transaction->start_time)) : '';
        $transaction->end_time_formatted = $transaction->end_time ? date('F d, Y h:i A', strtotime($transaction->end_time)) : '';
        $transaction->service_price = $transaction->service->price ? $transaction->service->price : 0;
        $transaction->plus_time_price_total = $transaction->plus_time ? ($transaction->plus_time * $transaction->service->price_per_plus_time) / 15: 0;
        $plus_time_formatted = '';
        if ($transaction->plus_time > 0) {
            $plus_time_hrs = floor($transaction->plus_time/60);
            $plus_time_mins =$transaction->plus_time%60;

            if ($plus_time_hrs > 1) {
                $hours = $plus_time_hrs.' hrs';
            } else {
                $hours = $plus_time_hrs.' hr';
            }

            $value = '';
            if ($plus_time_hrs == 0) {
                $value = $plus_time_mins.' mins';
            } else if ($plus_time_mins == 0) {
                $value = $hours;
            } else {
                $value = $hours.' & '.$plus_time_mins.' mins';
            }
            $plus_time_formatted = $value;
        }

        $transaction->plus_time_formatted = $plus_time_formatted;

        $range = range(15, 300, 15);
        $plus_time = [];
        foreach ($range as $ranges) {
            $hrs = floor($ranges/60);
            $mins = $ranges%60;

            if ($hrs > 1) {
                $hours = $hrs.' hrs';
            } else {
                $hours = $hrs.' hr';
            }

            $value = '';
            if ($hrs == 0) {
                $value = $mins.' mins';
            } else if ($mins == 0) {
                $value = $hours;
            } else {
                $value = $hours.' & '.$mins.' mins';
            }

            $plus_time [$ranges] = $value;
        }

        $response = [
            'status' => true,
            'data' => [
                'transaction' => $transaction,
                'services' => Service::where('spa_id', $transaction->spa_id)->get(),
                'room' => $this->roomService->getRoomList($transaction->spa_id, $now),
                'plus_time' => $plus_time,
                'therapist_1' => $this->getTherapistAvailability($transaction->spa_id, $transaction->therapist_1, $transaction->start_time),
                'therapist_2' => $this->getTherapistAvailability($transaction->spa_id, $transaction->therapist_2, $transaction->start_time),
            ],
        ];

        return $response;
    }

    public function getTherapistAvailability($spa_id, $therapist_id, $dateTime)
    {
        $therapist = Therapist::where('spa_id', $spa_id)->get();

        $data = [];
        if (!empty($therapist)) {
            foreach ($therapist as $list) {
                $count_transactions = $this->therapistCount($therapist_id);
                $is_available = $this->therapistAvailability($spa_id, $therapist_id, $dateTime);
                $data [] = [
                    'therapist_id' => $list->id,
                    'fullname' => $list->user->fullname,
                    'count' => $count_transactions,
                    'availability' => $is_available ? 'yes' : 'no',
                ];
            }

            array_multisort(array_column($data, 'count'), $data);
        }

        return $data;
    }

    public function preparation_time()
    {
        $range = range(1, 30, 1);
        $prep_time = [];
        foreach ($range as $ranges) {
            $mins = $ranges%60;
            $value = '';
            if ($mins > 1) {
                $value = $mins.' mins';
            } else {
                $value = $mins.' min';
            }

            $prep_time [$ranges] = $value;
        }

        return $prep_time;
    }

    /**
     * @param $salesId
     * @param $clientId
     * @return bool
     */
    public function checkIfClientExists($salesId, $clientId): bool
    {
        $transaction = Transaction::where('client_id','=',$clientId)
            ->where('sales_id','=',$salesId)->count();

        return $transaction > 0;
    }

    public function getTransactions($spaId, $saleId)
    {
//        return Transaction::where('spa_id','=',$spaId)
//            ->where('sales_id','=',$saleId)
//            ->get();
        return Sale::with(['discounts','transactions' => function($transaction) use ($spaId){
            return $transaction->where('spa_id',$spaId)->get();
        }])
            ->where('id','=',$saleId)
            ->first();
    }

    public function getSoldVouchers($saleId)
    {
        return Discount::where('sale_id',$saleId)->get();
    }

    public function clientTransactionLists($spaId, $saleId)
    {
        $sales = $this->getTransactions($spaId, $saleId);
        $transactions = $this->getTransactions($spaId, $saleId)->transactions;
        return DataTables::of($transactions)
            ->editColumn('client_id',function($transaction){
                return '<a href="'.route('clients.show',['client' => $transaction->client->id]).'" target="_blank">'.$transaction->client->full_name.'</a>';
            })
            ->editColumn('amount', function($transaction){
                return '<span class="text-primary">'.number_format($transaction->service->price,2).'</span>';
            })
            ->editColumn('plus_time_amount', function($transaction){
                return '<span class="text-primary">'.number_format($transaction->price_per_plus_time_total,2).'</span>';
            })
            ->editColumn('service_id',function($transaction){
                return ucwords($transaction->service->name);
            })
            ->addColumn('payable_amount', function($transaction){
                return '<span class="text-danger text-bold">'.number_format($transaction->total_amount,2).'</span>';
            })
            ->editColumn('room_id', function($transaction){
                return '<span class="text-info text-bold">#'.$transaction->room_id.'</span>';
            })
            ->editColumn('spa_id', function($transaction){
                return $transaction->spa->name;
            })
            ->editColumn('commission_reference_amount', function($transaction){
                return number_format($transaction->commission_reference_amount,2);
            })
            ->editColumn('discount_amount',function($transaction){
                return '<span class="text-purple text-bold">'.number_format($transaction->discount_amount,2).'</span>';;
            })
            ->editColumn('start_date', function($transaction){
                return '<span class="text-primary">'.$transaction->start_date.'</span>';
            })
            ->editColumn('end_date', function($transaction){
                return '<span class="text-primary">'.$transaction->end_date.'</span>';
            })
            ->editColumn('duration', function($transaction){
                return '<span class="text-danger text-bold">'.$transaction->service->duration.' mins</span>';
            })
            ->editColumn('plus_time', function($transaction){
                return '<span class="text-success text-bold">'.$transaction->plus_time.' mins</span>';
            })
            ->editColumn('total_time', function($transaction){
                return '<span class="text-fuchsia text-bold">' . ($transaction->plus_time + $transaction->service->duration) .' mins</span>';
            })
            ->addColumn('status',function($transaction){
                return Carbon::parse($transaction->end_time) >= now() ? '<span class="badge badge-info">on-going</span>' : '<span class="badge badge-success">completed</span>';
            })
            ->addColumn('therapists', function($transaction){
                $therapist = '<span class="badge badge-info m-1">'.$transaction->therapist->full_name.'</span>';
                if($transaction->therapist2 !== null) $therapist .= '<span class="badge badge-success m-1">'.$transaction->therapist2->full_name.'</span>';

                return $therapist;

            })
            ->addColumn('edit',function($transaction){
                $action = '';
                if(auth()->user()->hasRole('owner'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary m-1 edit-transaction" id="'.$transaction->id.'" title="Edit Transaction"><i class="fa fa-pen"></i></button>';
                }
                return $action;
            })
            ->addColumn('extend_time', function($transaction){
                $option = '';
                if($transaction->sale->payment_status === 'pending')
                {
                    $option .= '<select class="form-control extend_time" id="'.$transaction->id.'">';
                    $option .= '<option value="">--Select--</option>';
                    for($minute = 15; $minute <= 120; $minute = $minute + 5)
                    {
                        $option .= '<option value="'.$minute.'">'.$minute.' mins</option>';
                    }
                    $option .= '</select>';
                }

                    return $option;
            })
            ->addColumn('isolate', function($transaction){
                if($transaction->sale->payment_status === 'pending' && $transaction->countNumberOfClientExistMoreThanOnceInSalesTransaction($transaction->sales_id, $transaction->client_id) < 2)
                {
                    return '<button type="button" class="btn btn-sm btn-outline-info isolate" id="'.$transaction->id.'" title="Isolate"><span class="fa fa-level-up-alt"></span></button>';
                }
                return '';
            })
            ->addColumn('action',function($transaction){
                $action = "";
                if(Carbon::parse($transaction->end_time) > now() && $transaction->sale->payment_status === 'pending')
                {
                    $action .= '<button type="button" class="btn btn-sm btn-outline-danger m-1 void-transaction" id="'.$transaction->id.'" title="Void Transaction"><i class="fas fa-times"></i></button>';
                }
                return $action;
            })
            ->addColumn('under_time',function($transaction){
                $action = "";
                if(Carbon::parse($transaction->end_time) > now())
                {
                    $action .= '<button type="button" class="btn btn-sm btn-outline-danger m-1 under-time" id="'.$transaction->id.'" title="Under time"><i class="fa fa-clock"></i></button>';
                }
                return $action;
            })
            ->addColumn('apply_discount', function($transaction){
                $action = '';
                if($transaction->sale->payment_status !== 'completed')
                {
                    if(is_null($transaction->discount_id))
                    {
                        $action .= '<button type="button" class="btn btn-sm btn-outline-success m-1 apply-discount" id="'.$transaction->id.'" title="Apply Discount"><i class="fa fa-tag"></i></button>';
                    }else{
                        $action .= '<button type="button" class="btn btn-sm bg-orange m-1 remove-discount" id="'.$transaction->id.'" title="Apply Discount"><i class="fa fa-trash"></i></button>';
                    }
                }

                return $action;
            })
            ->setRowId(function($transaction){
                return 'view-'.$transaction->id;
            })
            ->setRowClass(function ($transaction) {
                return Carbon::parse($transaction->end_time) >= now() ? 'on-going-transaction' : '';
            })
            ->rawColumns(['edit','client_id','plus_time_amount','payable_amount','total_time','discount_amount','extend_time','isolate','action','status','amount','room_id','therapists','start_date','end_date','duration','plus_time','under_time','apply_discount'])
            ->with([
                'sale_status' => $sales->payment_status,
                'vouchers' => $sales->discounts,
                'total_vouchers_amount' => $total_voucher = collect($sales->discounts)->sum('price'),
                'total_amount' => number_format(collect($transactions)->sum('amount') + $total_voucher,2),
                'total_clients' => collect($transactions)->count(),
                'payment_status' => collect($transactions)->first() !== null ? collect($transactions)->first()->sale->payment_status : 0,
                'amount_paid' => collect($transactions)->first() !== null ? number_format(collect($transactions)->first()->sale->amount_paid,2) : 0,
                'change' => collect($transactions)->first() !== null ? number_format(collect($transactions)->first()->sale->change,2) : 0,
                'payment_method' => collect($transactions)->first() !== null ? !is_null(collect($transactions)->first()->sale->payment_method) ? collect($transactions)->first()->sale->payment_method : '' : '',
                'non_cash_amount' => collect($transactions)->first() !== null ?
                    !is_null(collect($transactions)->first()->sale->non_cash_payment) ? number_format(collect($transactions)->first()->sale->non_cash_payment->non_cash_amount,2) : '' : '',
                'cash_amount' => collect($transactions)->first() !== null ?
                    !is_null(collect($transactions)->first()->sale->non_cash_payment) ? number_format(collect($transactions)->first()->sale->non_cash_payment->cash_amount,2) : '' : '',
            ])
            ->make(true);
    }
}
