<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasMany(Transaction::class, 'therapist_1', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->user->firstname} {$this->user->lastname}";
    }
    public function spas()
    {
        return $this->belongsTo(Spa::class, 'spa_id');
    }
}
