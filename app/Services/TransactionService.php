<?php

namespace App\Services;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Therapist;
use App\Models\Sale;
use App\Models\Client;
use Carbon\Carbon;
use App\Services\RoomService;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    private $roomService;

    public function __construct(
        RoomService $roomService,
    ) {
        $this->roomService = $roomService;
    }

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
        $transaction = Transaction::where('therapist_1', $therapist_id)
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
        $transaction->amount_formatted = number_format($transaction->amount, 2);

        $range = range(15, 300, 15);
        $plus_time = [];
        foreach ($range as $ranges) {
            $hrs = floor($ranges/60);
            $mins = $ranges%60;

            if ($hrs > 0) {
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
                'therapist_1' => $this->getTherapistAvailability($transaction->spa_id, $transaction->therapist_1, $transaction->end_time),
                'therapist_2' => $this->getTherapistAvailability($transaction->spa_id, $transaction->therapist_2, $transaction->end_time),
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
}
