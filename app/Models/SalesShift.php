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

    public function cash($shiftId)
    {
        return Payment::where('sales_shift_id',$shiftId)->sum('payment');
    }

    public function nonCash($shiftId)
    {
        return Payment::where('sales_shift_id',$shiftId)->sum('non_cash_payment');
    }
}
