<?php

namespace App\Http\Controllers;

use App\Models\SalesShift;
use App\Services\PointOfSales\Shift\ShiftService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Services\SalesShiftService;

class SalesShiftController extends Controller
{
    private $salesShiftService;

    public function __construct(SalesShiftService $salesShiftService)
    {
        $this->salesShiftService = $salesShiftService;
    }

    public function index($id)
    {
        return $this->salesShiftService->get_shift($id);
    }

    public function create($spa_id)
    {
        return $this->salesShiftService->start_shift($spa_id);
    }

    public function edit($id, $amount, $type)
    {
        if ($type == 'start_money') {
            return $this->salesShiftService->start_money($id, $amount);
        } else if ($type == 'end_shift') {
            return $this->salesShiftService->end_shift($id);
        }
    }

}
