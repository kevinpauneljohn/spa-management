<?php

namespace App\Services\PointOfSales\Sales;

use App\Models\Payment;

class PaymentService
{
    public function salePaymentsCash($shiftId)
    {
        return Payment::where('sales_shift_id',$shiftId)->where('payment_type','Cash')->get();
    }
}
