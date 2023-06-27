<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $owner = auth()->user()->owner;

        $spa = $owner->spas;

        return view('Report.index', ['spa' => $spa, 'owner' => $owner->id]);
    }
    
    public function getSales(Request $request, $owner_id)
    {
        return $this->reportService->salesReport($request, $owner_id);
    }
}
