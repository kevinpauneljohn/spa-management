<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Sale extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'spa_id',
        'amount_paid',
        'payment_status',
        'user_id',
        'appointment_batch',
        'payment_method',
        'payment_account_number',
        'payment_bank_name'
    ];

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }
}
