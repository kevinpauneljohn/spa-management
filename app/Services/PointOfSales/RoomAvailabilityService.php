<?php

namespace App\Services\PointOfSales;

use Illuminate\Support\Collection;

class RoomAvailabilityService extends MasseurAvailabilityService
{
    /**
     * @param $spaId
     * @return Collection
     */
    public function availableRooms($spaId): Collection
    {
        $excluded = $this->excludedRooms($spaId);
        return collect($this->setupRoomArray($spaId))->reject(function($value, $key) use ($excluded){
            $data = [];
            foreach ($excluded as $exclude)
            {
                if($value == $exclude)
                    $data[$key] = $value;
            }
            return $data;
        });
    }

    /**
     * this method will format the room ids as array
     * @param $spaId
     * @return array
     */
    private function setupRoomArray($spaId): array
    {
        $totalRooms = $this->getSpaNumberOfRooms($spaId);
        $availableRooms = [];
        for ($rooms = 1; $rooms <= $totalRooms; $rooms++)
        {
            $availableRooms[$rooms] = strval($rooms);
        }
        return collect($availableRooms)->flatten()->toArray();
    }

    /**
     * get all the room id to be excluded
     * @param $spaId
     * @return array
     */
    public function excludedRooms($spaId): array
    {
        return collect($this->transactions($spaId))->pluck('room_id')->toArray();
    }

    /**
     * get the sales transactions with pending payment status
     * @param $spaId
     * @return Collection
     */
    private function transactions($spaId): Collection
    {
        return collect($this->sale($spaId))->pluck('transactions')->flatten();
    }

    /**
     * get the spa number of rooms
     * @param $spaId
     * @return mixed
     */
    private function getSpaNumberOfRooms($spaId)
    {
        return $this->spa($spaId)->number_of_rooms;
    }

}
