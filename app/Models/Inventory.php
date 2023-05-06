<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['spa_id','owner_id','name','description','quantity','unit','category','sku'];

//    protected static $logAttributes = ['spa_id','name','description','unit','category','sku'];

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

}
