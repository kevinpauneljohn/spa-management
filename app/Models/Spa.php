<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spa extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'number_of_rooms',
    ];
    /**
     * @var mixed
     */
    public $displayExpensesFromDateRange;

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function therapists()
    {
        return $this->hasMany(Therapist::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function staff()
    {
        return $this->hasMany(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function displayExpensesFromDateRange($dateFrom, $dateTo): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->expenses()
            ->whereDate('created_at','>=',$dateFrom)
            ->whereDate('created_at','<=',$dateTo);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function displayTransactionsTherapistOneFromDateRange($dateFrom, $dateTo)
    {
        return $this->transactions()
            ->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);
    }

    public function displayTransactionsTherapistTwoFromDateRange($dateFrom, $dateTo)
    {
        return $this->transactions()
            ->where('therapist_2','!=',null)
            ->whereDate('start_time','>=',$dateFrom)
            ->whereDate('start_time','<=',$dateTo);
    }

}
