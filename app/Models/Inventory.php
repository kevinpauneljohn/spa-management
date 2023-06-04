<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['spa_id','owner_id','name','description','quantity','restock_limit','unit','category','sku'];

    protected static $logAttributes = ['spa_id','owner_id','name','description','quantity','restock_limit','unit','category','sku'];

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

}
