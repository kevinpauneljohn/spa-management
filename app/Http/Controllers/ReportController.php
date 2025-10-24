<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Support\Facades\Route;

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

        return view('Report.index', ['spas' => $spa, 'owner' => $owner->id]);
    }

    public function getSales(Request $request, $owner_id)
    {
        return $this->reportService->salesReport($request, $owner_id);
    }

    public function displayProfitByDateRange($spa, Request $request): \Illuminate\Http\JsonResponse
    {
        $date = explode('-',$request->input('date'));
        $startDate = Carbon::parse($date[0]);
        $endDate = Carbon::parse($date[1]);
        return response()->json([
            'sales' => number_format($this->reportService->sales($spa, $startDate, $endDate),2),
            'expenses' => number_format($this->reportService->expenses($spa, $startDate, $endDate),2),
            'profit' => number_format($this->reportService->profit($spa, $startDate, $endDate),2),
            'startDate' => $startDate->format('M-d-Y'),
            'endDate' => $endDate->format('M-d-Y')
        ]);
    }
}
