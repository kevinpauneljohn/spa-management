<?php

namespace App\Services\PointOfSales\Sales;

use App\Models\Payment;
use App\Models\SalesShift;
use Spatie\Activitylog\Contracts\Activity;

class ClientPayment extends AmountToBePaid
{
    protected $shift_id;
    public function __construct()
    {
        $this->shift_id = $this->getSalesShift()->id;
    }
    private function getSalesShift()
    {
        return SalesShift::where('user_id',auth()->user()->id)->where('completed',false)->first();
    }

    /**
     * update the cash on drawer
     * @param $amountPaid
     * @param $change
     * @return void
     */
    private function updateCashDrawer($amountPaid, $change): void
    {
        $salesShift = SalesShift::find($this->shift_id);
        $salesShift->start_money = ($salesShift->start_money + $amountPaid) - $change;
        $salesShift->save();
    }

    /**
     * @param $paymentType
     * @param $amount
     * @param $referenceNo
     * @return void
     */
    private function saveSalesShiftPayments($paymentType, $amount, $referenceNo): void
    {
        Payment::create([
            'sales_shift_id' => $this->shift_id,
            'payment' => $amount,
            'payment_type' => $paymentType,
            'reference_number' => $referenceNo
        ]);
    }
    public function payment($salesId, $paymentType, $amount, $referenceNo): bool
    {
        if($paymentType === 'Cash')
        {
            return $this->cash($salesId, $paymentType, $amount);
        }
        else{
            return $this->nonCash($salesId, $paymentType, $referenceNo);
        }
    }

    /**
     * payment process for cash payment type
     * @param $salesId
     * @param $paymentType
     * @param $amount
     * @return bool
     */
    private function cash($salesId, $paymentType, $amount): bool
    {
        if($this->salesTransactions($salesId)->sum('amount') <= $amount)
        {
            $sum = $this->salesTransactions($salesId)->sum('amount');

            $sales = $this->sales($salesId);
            $sales->payment_method = $paymentType;
            $sales->amount_paid = $amount;
            $sales->total_amount = $sum;
            $sales->change = $amount - $sum;
            $sales->payment_status = 'completed';
            $sales->paid_at = now();

            if($sales->save())
            {
                $this->saveSalesShiftPayments($paymentType, $amount, null);
                $this->updateCashDrawer($sales->amount_paid, $sales->change);
                $this->activityLogs($sales, $amount);
                return true;
            }
        }
        return false;
    }

    /**
     * payment saving for non-cash payment type
     * @param $salesId
     * @param $paymentType
     * @param $referenceNo
     * @return bool
     */
    private function nonCash($salesId, $paymentType, $referenceNo): bool
    {
        if($referenceNo !== null)
        {
            $sales = $this->sales($salesId);
            $sales->payment_method = $paymentType;
            $sales->amount_paid = $this->salesTransactions($salesId)->sum('amount');
            $sales->payment_status = 'completed';
            $sales->payment_account_number = $referenceNo;
            $sales->paid_at = now();

            if($sales->save())
            {
                $this->saveSalesShiftPayments($paymentType, $sales->amount_paid, $referenceNo);
                $this->activityLogs($sales, 0);
                return true;
            }
        }
        return false;
    }

    /**
     * this will log the action taken by the authenticated user
     * @param $sales
     * @param $client_cash
     * @return void
     */
    private function activityLogs($sales, $client_cash)
    {
        activity('sales_payment')->causedBy(auth()->user()->id)
            ->withProperties(collect($sales)->merge([
                'client_cash' => $client_cash,
                'caused_by' => auth()->user()->id,
                'causer_name' => auth()->user()->fullname,
                'transactions' => collect($sales->transactions)->toArray(),
                'total_amount' => $this->totalAmount($sales->id)
            ]))
            ->tap(function(Activity $activity) use ($sales){
                $activity->spa_id = $sales->spa_id;
            })
            ->log('Sales Payment');
    }
}
