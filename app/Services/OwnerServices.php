<?php

namespace App\Services;

use App\Models\Spa;

class OwnerServices
{
    public $spa;



    public function getOwnerBySpaID($spaId)
    {
        $spa = Spa::findOrFail($spaId);
        return $spa->owner;
    }
}
