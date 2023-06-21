<?php

namespace App\Services;

use Yajra\DataTables\DataTables;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Spa;
use App\Models\Therapist;
use App\Models\Transaction;

use App\Services\TransactionService;
use App\Services\RoomService;

class PosService
{
    private $transactionService;
    private $roomService;

    public function __construct(TransactionService $transactionService, RoomService $roomService)
    {
        $this->transactionService = $transactionService;
        $this->roomService = $roomService;
    }
    
    public function getPosApi($spa_id, $request)
    {
        $spa = Spa::findOrFail($spa_id);

        $request_date = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        if (!empty($request->date)) {
            $request_date = date('Y-m-d H:i:s', strtotime($request->date));
        }

        $data = [
            'therapist' => $this->getTherapistList($spa->id, $request_date),
            'rooms' => $this->roomService->getRoomList($spa->id, $request_date),
            'services' => $this->getServices($spa->id),
            'count_guest' => '',
            'count_upcoming' => '',
            'spa_record' => '',
        ];

        return $data;
    }

    public function getTherapistList($spa_id, $request)
    {
        $request_date = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        if ($request) {
            $request_date = Carbon::parse($request)->format('Y-m-d H:i:s');
        }

        $therapist = Therapist::where('spa_id', $spa_id)->get();
        
        $data = [];
        if (!empty($therapist)) {
            foreach ($therapist as $list) {
                $count_transactions = $this->transactionService->therapistCount($list->id);
                $is_available = $this->transactionService->therapistAvailability($spa_id, $list->id, $request_date);
                $data [] = [
                    'therapist_id' => $list->id,
                    'fullname' => $list->user->fullname,
                    'count' => $count_transactions,
                    'availability' => $is_available ? 'yes' : 'no',
                ];                
            }
    
            array_multisort(array_column($data, 'count'), $data);
        }

        return $data;
    }

    public function getRoomList($spa_id, $request)
    {
        $request_date = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        if ($request) {
            $request_date = Carbon::parse($request)->format('Y-m-d H:i:s');
        }

        $data = $this->roomService->getRoomList($spa_id, $request_date);

        return $data;
    }

    public function getServices($spa_id)
    {
        $service = Service::where('spa_id', $spa_id)->pluck('id', 'name');

        return $service;
    }
}
