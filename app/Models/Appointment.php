<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UsesUuid;

class Appointment extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'client_id',
        'service_id',
        'service_name',
        'batch',
        'amount',
        'start_time',
        'appointment_type',
        'social_media_type',
        'appointment_status',
        'primary'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }
}
