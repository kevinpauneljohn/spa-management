<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shift;
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','guard_name'
    ];

    public function shift()
    {
        return $this->hasMany(Shift::class);
    }
}
