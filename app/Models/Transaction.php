<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'service_id',
        'service_name',
        'amount',
        'commission_reference_amount',
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
        'void',
        'user_id',
        'void_reason',
        'discount_amount',
        'discount_id'
    ];

    protected static $logAttributes = [
        'service_name',
        'amount',
        'therapist_1',
        'therapist_2',
        'client_id',
        'start_time',
        'end_time',
        'sales_type',
        'sales_id',
        'room_id',
        'void',
        'void_reason'
    ];
    protected $appends = ['client_name','start_date','end_date','gross_sale','price_per_plus_time_total',
        'total_amount','plus_time_multiplicand','end_time_twelve_hour_format','appointment_date'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class, 'therapist_1', 'id');
    }

    public function therapist2(): BelongsTo
    {
        return $this->belongsTo(Therapist::class, 'therapist_2', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function spa(): BelongsTo
    {
        return $this->belongsTo(Spa::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class,'sales_id');
    }

    public function getClientNameAttribute(): string
    {
        $client = DB::table('clients')
            ->where('id',$this->client_id)->first();
        return "{$client->firstname} {$client->lastname}";
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function getStartDateAttribute(): string
    {
        return Carbon::parse($this->start_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');
    }

    public function getEndDateAttribute(): string
    {
        return Carbon::parse($this->end_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');
    }

    public function getGrossSaleAttribute()
    {
        return $this->therapist_2 !== null ? $this->commission_reference_amount / 2 : $this->commission_reference_amount;
    }

    public function getPricePerPlusTimeTotalAttribute()
    {
        return $this->plus_time_multiplicand * $this->service->price_per_plus_time;
    }

    public function getTotalAmountAttribute()
    {
        return ($this->getPricePerPlusTimeTotalAttribute() + $this->service->price) - $this->discount_amount;
    }

    public function getPlusTimeMultiplicandAttribute()
    {
        return $this->plus_time / 15;
    }

    public function getEndTimeTwelveHourFormatAttribute()
    {
        return Carbon::parse($this->end_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i:s');
    }

    public function getAppointmentDateAttribute()
    {
        return Carbon::parse($this->start_time)->setTimezone('Asia/Manila')->format('Y-m-d h:i A');
    }

    public function countNumberOfClientExistMoreThanOnceInSalesTransaction($sales_id, $client_id)
    {
        return Transaction::where('sales_id',$sales_id)->where('client_id',$client_id)->count();
    }
}
