<?php

namespace App\Services\PointOfSales;

use App\Models\Sale;
use App\Models\Therapist;

class MasseurAvailabilityService extends TransactionService
{
    /**
     * @param $spaId
     * @return mixed
     */
    public function masseurs($spaId)
    {
        return Therapist::where('spa_id','=',$spaId)
            ->whereNotIn('id', $this->excludeMasseur($spaId))->get();
    }

    /**
     * get all the therapist id to be excluded
     * @param $spaId
     * @return array
     */
    private function excludeMasseur($spaId): array
    {
        return $this->getMasseursInTransactions($spaId)->keys()->filter(function($value, $key){
            return is_string($value) && !empty($value);
        })->toArray();

    }

    private function getMasseursInTransactions($spaId): \Illuminate\Support\Collection
    {
        $therapist = collect($this->sale($spaId))->pluck('transactions')->flatten();
        $therapistOne = collect($therapist)->groupBy('therapist_1');
        $therapistTwo = collect($therapist)->groupBy('therapist_2');

        return collect($therapistOne)->merge($therapistTwo);
    }

    public function displayProgressBar($spaId)
    {
        return $this->getMasseursInTransactions($spaId);
    }
}
