<?php

namespace App\Services\PointOfSales;

use App\Models\Client;
use App\Models\Discount;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Spa;
use App\Models\Transaction;
use App\Services\PointOfSales\Sales\SalesService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Contracts\Activity;

class TransactionService extends SalesService
{
    protected $owner;

    public function __construct(UserService $userService)
    {
        $this->owner = $userService->get_staff_owner();
    }

    /**
     * @param Request $request
     * @return null
     */
    public function saveClient(Request $request)
    {
        if(collect($request->all())->has('client_id'))
        {
            $transaction = $this->saveOldClientToTransactions($request);
            $this->logActivity($transaction);
        }else{
            $transaction = $this->saveNewClientToTransactions($request);
            $this->logActivity($transaction);
        }
        return $transaction;
    }

    /**
     * log the action
     * @param $transaction
     * @return void
     */
    private function logActivity($transaction)
    {
        activity()->causedBy(auth()->user()->id)
            ->withProperties(collect($transaction)
                ->merge([
                    'table' => 'transactions',
                    'caused_by' => auth()->user()->id,
                    'causer_name' => auth()->user()->fullname,
                    'client_name' => $transaction->client->full_name,
                    'therapist_1_name' => $transaction->therapist->full_name,
                    'therapist_2_name' => $transaction->therapist2 != null ? $transaction->therapist2->full_name : '',
                ]))
            ->tap(function(Activity $activity) use ($transaction){
                $activity->spa_id = $transaction->spa_id;
            })
            ->log('created transaction');
    }

    /**
     * Old or existing clients will be saved to transactions
     * @param Request $request
     * @return mixed
     */
    private function saveOldClientToTransactions(Request $request)
    {
        $clientId = $request->client_id;
        return Transaction::create($this->dataFormatted($request, $clientId));
    }


    /**
     * new client will be saved to transactions
     * @param Request $request
     * @return mixed
     */
    private function saveNewClientToTransactions(Request $request)
    {
        if($this->clientExistsFromTheClientsTable($request))
        {
            //retrieve the client instance
            $client = $this->client($request)->first();
        }else{
            //save the client to the clients table first
            $client = $this->storeClientToTheClientsTable($request);
            //then save it to the client_owner table
            $this->saveClientOwnerRelationship($client->id, $this->owner->id);
        }

        return Transaction::create($this->dataFormatted($request, $client->id));
    }

    /**
     * @param $clientId
     * @param $ownerId
     * @return void
     */
    protected function saveClientOwnerRelationship($clientId, $ownerId): void
    {
        DB::table('client_owner')->insert([
            'client_id' => $clientId,
            'owner_id' => $ownerId
        ]);
    }

    /**
     * return true if the client exists in the clients table
     * @param $request
     * @return bool
     */
    protected function clientExistsFromTheClientsTable($request): bool
    {
        return $this->client($request)->count() > 0;
    }

    /**
     * save to the clients table
     * @param $request
     * @return mixed
     */
    protected function storeClientToTheClientsTable($request)
    {
        return Client::create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'date_of_birth' => $request->date_of_birth,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'address' => $request->address,
        ]);
    }

    /**
     * get the client
     * @param $request
     * @return mixed
     */
    protected function client($request)
    {
        return Client::where('firstname','=',$request->firstname)
            ->where('middlename','=',$request->middlename)
            ->where('lastname','=',$request->lastname)
            ->where('mobile_number','=',$request->mobile_number)
            ->where('email','=',$request->email);
    }

    /**
     * @param $request
     * @param $clientId
     * @return array
     */
    private function dataFormatted($request, $clientId): array
    {
        $service = $this->service($request->service_id);
        $startTime = $this->startTime($request->preparation_time);
        $endTime = $this->endTime($startTime, $service->duration, $request->plus_time);

        return [
            'spa_id' => $request->spa_id,
            'service_id' => $request->service_id,
            'service_name' => $service->name,
            'amount' => $this->updateAmount('add',$service->price, $request->plus_time, $service->price_per_plus_time),
            'commission_reference_amount' => $this->updateAmount('add',$service->commission_reference_amount, $request->plus_time, $service->price_per_plus_time),
            'therapist_1' => $request->therapist_1,
            'therapist_2' => $request->therapist_2,
            'client_id' => $clientId,
            'preparation_time' => $request->preparation_time,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'plus_time' => $request->plus_time,
            'rating' => 0,
            'sales_type' => $request->appointment_type,
            'sales_id' => $request->sales_id,
            'room_id' => $request->room,
            'void' => false
        ];
    }

    /**
     * get the service instance
     * @param $service
     * @return mixed
     */
    private function service($service)
    {
        return Service::find($service);
    }

    /**
     * transaction start time
     * @param $preparationTime
     * @return \Illuminate\Support\Carbon
     */
    public function startTime($preparationTime): \Illuminate\Support\Carbon
    {
        return now()->addMinutes($preparationTime === null ? 0 : $preparationTime);
    }

    /**
     * transaction end time
     * @param $startTime
     * @param $serviceDuration
     * @param $plusTime
     * @return Carbon
     */
    public function endTime($startTime, $serviceDuration, $plusTime): Carbon
    {
        return Carbon::parse(
            Carbon::parse($startTime)->addMinutes($serviceDuration)
        )->addMinutes($plusTime !== null ? $plusTime : 0);
    }

    /**
     * check the client if it exists in the sales transaction instance
     * @param $salesId
     * @param $clientId
     * @return bool
     */
    public function checkIfClientExistsFromTransactions($salesId, $clientId): bool
    {
        $transaction = Transaction::where('client_id','=',$clientId)
            ->where('sales_id','=',$salesId)->count();

        return $transaction > 0;
    }

    /**
     * @param $spaId
     * @return mixed
     */
    public function spa($spaId)
    {
        return Spa::find($spaId);
    }

    /**
     * @param $spaId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function sale($spaId)
    {
        return Sale::with(['transactions' => function($transaction){
            $transaction->where('end_time','>',now());
        }])->where('spa_id','=',$spaId)->get();
    }

    public function transaction($transactionId)
    {
        return Transaction::find($transactionId);
    }

    /**
     * @param $transactionId
     * @param $plusTime
     * @return bool
     */
    public function extendTime($transactionId, $plusTime): bool
    {
        $transaction = $this->transaction($transactionId);
        $existingPlusTime = $transaction->plus_time;
        $transaction->plus_time = $existingPlusTime + $plusTime;
        $transaction->end_time = $this->updateEndTime('add',$transaction->end_time, $plusTime);
        $transaction->amount = $this->updateAmount('add',
            $transaction->amount,
            $plusTime,
            $transaction->service->price_per_plus_time);
        $transaction->commission_reference_amount = $this->updateAmount('add',
            $transaction->commission_reference_amount,
            $plusTime,
            $transaction->service->price_per_plus_time);

        if($transaction->isDirty())
        {
            return (bool)$transaction->save();
        }
        return false;
    }

    /**
     * @param $action
     * @param $endTime
     * @param $minutes
     * @return Carbon
     */
    private function updateEndTime($action, $endTime, $minutes): Carbon
    {
        if($action === 'add')
        {
            return $this->increaseEndTime($endTime, $minutes);
        }else{
            return $this->decreaseEndTime($endTime, $minutes);
        }
    }

    private function updateAmount($action, $originalAmount, $additionalMinutes, $price_per_plus_time)
    {
        if($action === 'add')
        {
            return $this->increaseAmount($originalAmount,$additionalMinutes, $price_per_plus_time);
        }else{
            return $this->decreaseAmount($originalAmount,$additionalMinutes, $price_per_plus_time);
        }
    }

    /**
     * @param $endTime
     * @param $additionalTime
     * @return Carbon
     */
    private function increaseEndTime($endTime, $additionalTime): Carbon
    {
        return Carbon::parse($endTime)->addMinutes($additionalTime);
    }

    /**
     * @param $endTime
     * @param $subtractedTime
     * @return Carbon
     */
    private function decreaseEndTime($endTime, $subtractedTime): Carbon
    {
        return Carbon::parse($endTime)->subMinutes($subtractedTime);
    }

    private function increaseAmount($originalAmount, $additionalMinutes, $price_per_plus_time)
    {
        $additionalAmount = ($additionalMinutes / 15) * $price_per_plus_time;
        return $originalAmount + $additionalAmount;
    }

    private function decreaseAmount($originalAmount, $additionalMinutes, $price_per_plus_time)
    {
        $additionalAmount = ($additionalMinutes / 15) * $price_per_plus_time;
        return $originalAmount - $additionalAmount;
    }

    public function underTime($transactionId): bool
    {
        return (bool)$this->transaction($transactionId)->fill(['end_time' => now()])->save();
    }

    public function buyVoucher($voucherId, $salesId): bool
    {
        if(!$this->isSalesIdExists($salesId))
            return false;

        $voucher = Discount::find($voucherId);
        $voucher->sale_id = $salesId;
        return (bool)$voucher->save();
    }
}
