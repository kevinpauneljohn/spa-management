<?php

namespace App\Services\PointOfSales\Sales;

use App\Models\Sale;

class AmountToBePaid
{
    public function invoiceDetails($salesId): array
    {
        return [
            'total_amount' => $this->totalAmount($salesId),
        ];
    }

    protected function sales($salesId)
    {
        return Sale::find($salesId);
    }

    protected function salesTransactions($salesId)
    {
        return $this->sales($salesId)->transactions;
    }

    protected function totalAmount($salesId)
    {
        return $this->salesTransactions($salesId)->sum('amount');
    }
}
