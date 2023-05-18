<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Sale;
use Carbon\Carbon;

class SaleController extends Controller
{
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
                if(auth()->user()->can('view invoices') || auth()->user()->hasRole('owner')) {
                    if ($sale->payment_status != 'paid') {
                        $action .= '<a href="#" data-status="'.$status.'" data-payment="'.$payment.'" data-account="'.$account.'" data-bank="'.$bank.'" data-invoice="'.$invoice_id.'" class="btn btn-xs btn-outline-primary rounded update-invoice" id="'.$sale->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                    } else if (auth()->user()->hasRole('owner')) {
                        $action .= '<a href="#" data-status="'.$status.'" data-payment="'.$payment.'" data-account="'.$account.'" data-bank="'.$bank.'" data-invoice="'.$invoice_id.'" class="btn btn-xs btn-outline-primary rounded update-invoice" id="'.$sale->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
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
        $sale = Sale::findOrFail($id);

        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->payment_account_number = $request->payment_account_number;
        $sale->payment_bank_name = $request->payment_bank_name;

        $status = false;
        $message = 'Unable to update sales. Please try again.';
        if ($sale->save()) {
            $status = true;
            $message = 'Sales has been updated.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }
}
