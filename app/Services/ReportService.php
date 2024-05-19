<?php

namespace App\Services;

use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Spa;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function salesReport($request, $owner_id)
    {
        $spa_ids = [];
        if ($request->type == 'all') {
            $spa = Spa::where('owner_id', $owner_id)->get();
            foreach ($spa as $key => $value) {
                $spa_ids[] = $value->id;
            }
        } else {
            $spa_ids[] = $request->spa_id;
        }

        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        $currentMonth = Carbon::now()->month;
        $currentYearSalesData = $this->getSalesDataForYear($currentYear, $spa_ids);
        $lastYearSalesData = $this->getSalesDataForYear($lastYear, $spa_ids);
        $currentYearVisitorData = $this->getDataVisitorForYear($currentYear, $spa_ids);
        $lastYearVisitorData = $this->getDataVisitorForYear($lastYear, $spa_ids);
        $labels = [];
        $currentYearSalesValues = [];
        $lastYearSalesValues = [];
        $currentYearVisitorsValues = [];
        $lastYearVisitorsValues = [];

        foreach (range(1, 12) as $month) {
            $labels[] = Carbon::createFromDate($currentYear, $month, 1)->format('M');
            $currentYearSalesValues[] = $currentYearSalesData[$month] ?? 0;
            $lastYearSalesValues[] = $lastYearSalesData[$month] ?? 0;
            $currentYearVisitorsValues[] = $currentYearVisitorData[$month] ?? 0;
            $lastYearVisitorsValues[] = $lastYearVisitorData[$month] ?? 0;
        }

        $lastMonthSalesComparison = $this->getMonthlySalesPercentageChange($spa_ids, $currentYear, $currentMonth - 1);
        $lastTwoMonthSalesComparison = $this->getMonthlySalesPercentageChange($spa_ids, $currentYear, $currentMonth - 2);
        $currentMonthSales = $currentYearSalesData[$currentMonth] ?? 0;
        $percentageSalesStatus = false;
        if ($lastMonthSalesComparison > $lastTwoMonthSalesComparison) {
            $percentageSalesStatus = true;
        }

        $lastMonthVisitorsComparison = $this->getMonthlyVisitorPercentageChange($spa_ids, $currentYear, $currentMonth - 1);
        $lastTwoMonthVisitorsComparison = $this->getMonthlyVisitorPercentageChange($spa_ids, $currentYear, $currentMonth - 2);
        $currentMonthVisitors = $currentYearVisitorData[$currentMonth] ?? 0;
        $percentageVisitorsStatus = false;
        if ($lastMonthVisitorsComparison > $lastTwoMonthVisitorsComparison) {
            $percentageVisitorsStatus = true;
        }
        $data = [
            'sales' => [
                'labels' => $labels,
                'currentYearValues' => $currentYearSalesValues,
                'lastYearValues' => $lastYearSalesValues,
                'lastMonthSalesComparison' => $lastMonthSalesComparison,
                'percentageSalesStatus' => $percentageSalesStatus,
                'currentMonthSales' => number_format($currentMonthSales, 2),
            ],
            'visitors' => [
                'labels' => $labels,
                'currentYearValues' => $currentYearVisitorsValues,
                'lastYearValues' => $lastYearVisitorsValues,
                'lastMonthVisitorsComparison' => $lastMonthVisitorsComparison,
                'percentageVisitorsStatus' => $percentageVisitorsStatus,
                'currentMonthVisitors' => $currentMonthVisitors,
            ]
        ];

        return response()->json($data);
    }

    protected function getSalesDataForYear($year, $spa_ids)
    {
        $startOfYear = Carbon::create($year, 1, 1);
        $endOfYear = Carbon::create($year, 12, 31);

        $data = [];
        for ($month = 0; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $sales = Sale::whereBetween('paid_at', [$startDate, $endDate])
                ->where('payment_status', 'completed')
                ->whereIn('spa_id', $spa_ids)
                ->sum('amount_paid');

            $data [] = $sales;
        }

        return $data;
    }

    protected function getMonthlySalesPercentageChange($spa_ids, $year, $month)
    {
        $currentMonthSales = Sale::whereYear('paid_at', $year)
            ->where('payment_status', 'completed')
            ->whereMonth('paid_at', $month)
            ->whereIn('spa_id', $spa_ids)
            ->sum('amount_paid');

        $lastMonth = Carbon::createFromDate($year, $month, 1)->subMonth();
        $lastMonthSales = Sale::whereYear('paid_at', $lastMonth->year)
            ->whereMonth('paid_at', $lastMonth->month)
            ->where('payment_status', 'completed')
            ->whereIn('spa_id', $spa_ids)
            ->sum('amount_paid');

        if ($lastMonthSales == 0) {
            $percentageChange = 0;
        } else {
            $percentageChange = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        }

        return number_format($percentageChange, 2);
    }

    protected function getDataVisitorForYear($year, $spa_ids)
    {
        $startOfYear = Carbon::create($year, 1, 1);
        $endOfYear = Carbon::create($year, 12, 31);

        $data = [];
        for ($month = 0; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $sales = Transaction::whereBetween('end_time', [$startDate, $endDate])
            ->where('amount','>', 0)
                ->whereIn('spa_id', $spa_ids)
                ->count();

            $data [] = $sales;
        }

        return $data;
    }

    protected function getMonthlyVisitorPercentageChange($spa_ids, $year, $month)
    {
        $currentMonthVisitors = Transaction::whereYear('end_time', $year)
            ->where('amount','>', 0)
            ->whereMonth('end_time', $month)
            ->whereIn('spa_id', $spa_ids)
            ->count();

        $lastMonth = Carbon::createFromDate($year, $month, 1)->subMonth();
        $lastMonthVisitor = Transaction::whereYear('end_time', $lastMonth->year)
            ->whereMonth('end_time', $lastMonth->month)
            ->where('amount','>', 0)
            ->whereIn('spa_id', $spa_ids)
            ->count();

        if ($lastMonthVisitor == 0) {
            $percentageChange = 0;
        } else {
            $percentageChange = (($currentMonthVisitors - $lastMonthVisitor) / $lastMonthVisitor) * 100;
        }

        return number_format($percentageChange, 2);
    }
}
