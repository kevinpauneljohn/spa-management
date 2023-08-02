<?php

namespace App\Services\PointOfSales\Sales;

use App\Models\Sale;
use App\Models\Transaction;
use Spatie\Activitylog\Contracts\Activity;

class IsolateTransaction extends AmountToBePaid
{
    public function isolateTransaction($spaId, $transactionId): bool
    {
        if($this->getTransaction($transactionId)->sale->transactions->count() > 1)
        {
            return $this->updateTransactionSalesId($spaId, $transactionId);
        }
        return false;
    }

    private function createSales($spaId)
    {
        return Sale::create([
            'spa_id' => $spaId,
            'amount_paid' => 0,
            'payment_status' => 'pending',
            'user_id' => auth()->user()->id,
        ]);
    }

    private function updateTransactionSalesId($spaId, $transactionId): bool
    {
        $previousSalesId = $this->getTransaction($transactionId)->sales_id;
        $sales = $this->createSales($spaId);
        $transaction = $this->getTransaction($transactionId);
        $transaction->sales_id = $sales->id;
        if($transaction->save())
        {
            activity('isolate')->causedBy(auth()->user()->id)
                ->withProperties(collect($transaction)->merge([
                    'caused_by' => auth()->user()->id,
                    'causer_name' => auth()->user()->fullname,
                    'client_name' => $transaction->client->full_name,
                    'previous_sales_id' => $previousSalesId
                ]))
                ->tap(function(Activity $activity) use ($transaction){
                    $activity->spa_id = $transaction->spa_id;
                })
                ->log('Client Isolated');
            return true;
        }
        return false;
    }

    private function getTransaction($transactionId)
    {
        return Transaction::find($transactionId);
    }

    private function checkSalesTransactionCount($transactionId)
    {
        return $this->getTransaction($transactionId)->sale->transactions->count();
    }

}
