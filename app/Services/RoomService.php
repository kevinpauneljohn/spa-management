<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Spa;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class RoomService
{
    public function getRoomList($id, $dateTime)
    {
        $spa = Spa::findOrFail($id);
        $rooms = range(1, $spa->number_of_rooms);

        $data = [];
        foreach ($rooms as $room) {
            $data [] = $this->getRoomAvailability($room, $id, $dateTime);
        }

        return $data;
    }

    public function getRoomAvailability($room, $spa_id, $dateTime)
    {
        $date = '';
        if ($dateTime != '') {
            $date = date('Y-m-d H:i:s', strtotime($dateTime));
        } else {
            $date = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        }
        
        $transaction = Transaction::where(
            'room_id', $room
        )->where('amount', '>', 0)
        ->where('spa_id', $spa_id)
        ->where('start_time', '<=', $date)
        ->where('end_time', '>=', $date)->with(['client'])->first();
        
        $dataList = [];
        $isAvailable = true;
        $isColorSet = 'bg-success';
        if (!empty($transaction)) {
            $dataList = $transaction;
            $isAvailable = false;
        }

        $data = [
            'room_id' => $room,
            'data' => $dataList,
            'is_available' => $isAvailable ? 'yes' : 'no'
        ];

        return $data;
    }
}
