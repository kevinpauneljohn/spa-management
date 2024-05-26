<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Therapist extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'user_id',
        'gender',
        'certificate',
        'commission_percentage',
        'commission_flat',
        'allowance',
        'offer_type',
        'is_excluded',
    ];

    protected $appends = ['full_name','commission','percentage'];

    public $therapist_one;
    public $therapist_two;

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }


    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class, 'therapist_1');
    }

    public function transactionsTherapistTwo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class,'therapist_2');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->user->firstname} {$this->user->lastname}";
    }
    public function spas()
    {
        return $this->belongsTo(Spa::class, 'spa_id');
    }

    public function getPercentageAttribute()
    {
        return $this->commission_percentage / 100;
    }

    private function setTransactionsDate($dateFrom, $dateTo)
    {
        $this->therapist_one = $this->transactions()->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);

        $this->therapist_two = $this->transactionsTherapistTwo()->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);
    }
    public function grossSales($dateFrom, $dateTo)
    {
        $this->setTransactionsDate($dateFrom, $dateTo);
        return collect($this->therapist_one->get())->concat($this->therapist_two->get())->map(function($item, $key){
            return $item['therapist_2'] == null ? $item['commission_reference_amount'] : $item['commission_reference_amount'] / 2;
        })->sum();
    }

    /**
     * this will get the total commission by percentage of by commission_reference_amount
     * @param $dateFrom
     * @param $dateTo
     * @return string
     */
    public function grossSalesCommission($dateFrom, $dateTo): string
    {
        //set date for the therapist_one and therapist_two properties
        $this->setTransactionsDate($dateFrom, $dateTo);

        return $this->offer_type === 'percentage_only'
        || $this->offer_type === 'percentage_plus_allowance'
            ? collect($this->therapist_one->get())->concat($this->therapist_two->get())->map(function($item, $key){
                return $item['therapist_2'] == null ? $item['commission_reference_amount'] : $item['commission_reference_amount'] / 2;
            })->sum() * ($this->commission_percentage / 100)
            : ($this->therapist_one->count() + $this->therapist_two->count()) * $this->commission_flat;
    }

    public function displayTransactionsFromDateRange($dateFrom, $dateTo): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->transactions()
            ->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);
    }

    public function getCommissionAttribute()
    {
        if($this->offer_type === 'percentage_plus_allowance' || $this->offer_type === 'percentage_only')
        {
            return $this->commission_percentage.'%';
        }
        return number_format($this->commission_flat,2);
    }
}
