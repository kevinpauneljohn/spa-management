<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','owner_id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
