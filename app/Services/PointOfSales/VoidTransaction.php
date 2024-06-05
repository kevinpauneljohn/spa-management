<?php

namespace App\Services\PointOfSales;

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
            $this->logActivity($transaction, $reason);
            $transaction->forceDelete();
            return true;
        }
        return false;
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

//    private function transaction($transactionId)
//    {
//        return Transaction::find($transactionId);
//    }



}
