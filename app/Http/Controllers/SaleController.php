<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Sale;
use Carbon\Carbon;
use App\Services\SaleService;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function lists($id)
    {
        $sale = Sale::where('spa_id', $id)
        ->with(['spa'])->get();

        return DataTables::of($sale)
            ->editColumn('spa',function($sale){
                return 'Invoice # '.substr($sale->id, -6);
            })
            ->addColumn('payment_status',function ($sale){
                return ucfirst($sale->payment_status);
            })
            ->addColumn('amount',function ($sale){
                return '&#8369; '.$sale->amount_paid;
            })
            ->addColumn('date',function ($sale){
                return Carbon::parse($sale->created_at)->setTimezone('Asia/Manila')->format('F d, Y h:i:s A');
            })
            ->addColumn('action', function($sale){
                $action = '';
                $invoice_id = 'Invoice # '.substr($sale->id, -6);
                $payment = $sale->payment_method;
                $account = $sale->payment_account_number;
                $bank = $sale->payment_bank_name;
                $status = $sale->payment_status;
                $batch = $sale->appointment_batch;
                if(auth()->user()->can('view invoices') || auth()->user()->hasRole('owner')) {
                    if ($sale->payment_status != 'paid') {
                        $action .= '<a href="#" data-batch="'.$batch.'" data-status="'.$status.'" data-payment="'.$payment.'" data-account="'.$account.'" data-bank="'.$bank.'" data-invoice="'.$invoice_id.'" class="btn btn-xs btn-outline-primary rounded update-invoice" id="'.$sale->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                    } else if (auth()->user()->hasRole('owner')) {
                        $action .= '<a href="#" data-batch="'.$batch.'" data-status="'.$status.'" data-payment="'.$payment.'" data-account="'.$account.'" data-bank="'.$bank.'" data-invoice="'.$invoice_id.'" class="btn btn-xs btn-outline-primary rounded update-invoice" id="'.$sale->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                    }
                    
                    $action .= '<a href="#" class="btn btn-xs btn-outline-success rounded view-invoice" id="'.$sale->id.'"><i class="fas fa-file-invoice"></i></a>&nbsp;';
                }

                return $action;
            })
            ->rawColumns(['action', 'amount'])
            ->make(true);
    }

    public function updateSales(Request $request, $id)
    {
        return $this->saleService->update($request, $id);
    }
}
