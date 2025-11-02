<?php

namespace App\Services\PointOfSales;

use App\Models\Sale;
use App\Models\Transaction;
use Spatie\Activitylog\Contracts\Activity;

class VoidTransaction extends TransactionService
{
    public function voidTransaction($transactionId, $reason): bool
    {
        $transaction = $this->transaction($transactionId);
        $transaction->void = true;
        $transaction->user_id = auth()->user()->id;
        $transaction->void_reason = $reason;
        $transaction->deleted_at = now();

        if(!is_null($transaction->discount_id))
        {
            $this->voidTransactionCoupon($transactionId);
        }

        if($transaction->save())
        {
            $sales = $transaction->sale;
            if($this->update_sales_total_after_transaction_voided(
                $sales->payment_status, $transaction->sales_id, $transaction->amount
                ))
            {
                $this->logActivity($transaction, $reason);
                $transaction->forceDelete();
            }
            return true;
        }
        return false;
    }

    private function update_sales_total_after_transaction_voided($paymentStatus, $salesId, $transactionAmount): bool
    {
        //if sales status was completed and voided by the owner, sales total amount will be deducted by the
        //transaction amount voided
        if($paymentStatus == "completed")
        {
            $sales = Sale::findOrfail($salesId);
            $sales->total_amount = $sales->total_amount - $transactionAmount;
            if($sales->save())
            {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * log the action
     * @param $transaction
     * @return void
     */
    private function logActivity($transaction, $reason)
    {
        activity()->causedBy(auth()->user()->id)
            ->withProperties(['table' => 'transactions',
                'transactionId' => $transaction->id,
                'sales_id' => $transaction->sales_id,
                'client_name' => $transaction->client->full_name,
                'service_name' => $transaction->service_name,
                'amount' => $transaction->amount,
                'start_time' => $transaction->start_time,
                'end_time' => $transaction->end_time,
                'caused_by' => auth()->user()->id,
                'causer_name' => auth()->user()->fullname,
                'void' => true,
                'void_reason' => $reason,
            ])
            ->tap(function(Activity $activity) use ($transaction){
                $activity->spa_id = $transaction->spa_id;
            })
            ->log('voided a transaction');
    }



}
