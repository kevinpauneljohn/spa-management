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
    ];

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'therapist_1');
    }

    public function transactionsTherapistTwo()
    {
        return $this->hasMany(Transaction::class,'therapist_2');
    }

    public function user()
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

    public function grossSalesCommission(): string
    {
        return $this->offer_type === 'percentage_only'
        || $this->offer_type === 'percentage_plus_allowance'
            ? $this->transactions()->sum('amount') * $this->percentage:
            $this->transactions()->count() * $this->commission_flat;
    }

    public function getClientNameAttribute()
    {

        return "{$this->firstname}";
    }

    public function displayTransactionsFromDateRange($dateFrom, $dateTo): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->transactions()
            ->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);
    }
}
