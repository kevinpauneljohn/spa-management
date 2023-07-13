<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        'room_id',
        'primary',
    ];

    protected $appends = ['client_name','start_date','end_date'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class, 'therapist_1', 'id');
    }

    public function therapist2()
    {
        return $this->belongsTo(Therapist::class, 'therapist_2', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function getClientNameAttribute()
    {
        $client = DB::table('clients')
            ->where('id',$this->client_id)->first();
        return "{$client->firstname} {$client->lastname}";
    }

    public function getStartDateAttribute()
    {
        return Carbon::parse($this->start_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');
    }

    public function getEndDateAttribute()
    {
        return Carbon::parse($this->end_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');
    }
}
