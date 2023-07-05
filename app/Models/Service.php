<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'spa_id',
        'name',
        'description',
        'duration',
        'price',
        'category',
        'price_per_plus_time'
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }
}
