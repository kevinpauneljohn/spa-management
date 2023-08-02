<?php

namespace App\Services\PointOfSales\Sales;

use Spatie\Activitylog\Contracts\Activity;

class ClientPayment extends AmountToBePaid
{
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
