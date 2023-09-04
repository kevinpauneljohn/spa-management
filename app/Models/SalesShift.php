<?php

namespace App\Models;

use App\Services\PointOfSales\Sales\PaymentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_shift',
        'end_shift',
        'user_id',
        'spa_id',
        'start_money',
        'completed'
    ];

    protected static $logAttributes = [
        'start_shift',
        'end_shift',
        'user_id',
        'spa_id',
        'start_money',
    ];

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function totalCash($shiftId)
    {
        return $this->formatPayment($this->cash($shiftId));

    }

    public function totalNonCash($shiftId)
    {
        return $this->formatPayment($this->nonCash($shiftId));

    }

    private function cash($shiftId)
    {
        return Payment::where('sales_shift_id',$shiftId)->where('payment_type','Cash')->get();
    }

    private function nonCash($shiftId)
    {
        return Payment::where('sales_shift_id',$shiftId)->where('payment_type','!=','Cash')->get();
    }

    private function formatPayment($payments)
    {
        $cashPayments = 0;
        foreach ($payments as $payment)
        {
            $cashPayments = $payment->payment_type == 'Cash' ? $cashPayments + $payment->sale->total_amount
                : $cashPayments + $payment->sale->amount_paid;
        }
        return $cashPayments;
    }
}
