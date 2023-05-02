<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'service_id',
        'service_name',
        'amount',
        'therapist_1',
        'therapist_2',
        'client_id',
        'start_time',
        'end_time',
        'plus_time',
        'discount_rate',
        'discount_amount',
        'tip',
        'rating',
        'sales_type',
        'sales_id',
        'room_id'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function therapist()
    {
        return $this->belongsTo(therapist::class);
    }

}
