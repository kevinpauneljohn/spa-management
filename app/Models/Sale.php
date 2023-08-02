<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'amount_paid',
        'payment_status',
        'user_id',
        'appointment_batch',
        'payment_method',
        'payment_account_number',
        'payment_bank_name',
        'paid_at'
    ];

    protected $appends = ['invoice_number'];
    public function spa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Spa::class);
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class,'sales_id');
    }

    public function getInvoiceNumberAttribute()
    {
        return substr($this->id,0,8);
    }

}
