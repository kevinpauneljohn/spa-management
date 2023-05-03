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
        'user_id'
    ];

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }
}
