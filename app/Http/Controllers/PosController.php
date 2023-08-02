<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Spa;
use Illuminate\Http\Request;
use App\Services\PosService;
use Config;

class PosController extends Controller
{
    private $posService;

    public function __construct(PosService $posService)
    {
        $this->posService = $posService;
    }

    public function getApi(Request $request, $spa_id)
    {
        return $this->posService->getPosApi($spa_id, $request);
    }

    public function getTherapistList(Request $request, $spa_id)
    {
        return $this->posService->getTherapistList($spa_id, $request->date);
    }

    public function getRoomList(Request $request, $spa_id)
    {
        return $this->posService->getRoomList($spa_id, $request->date);
    }
}
