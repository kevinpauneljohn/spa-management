<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Sale;

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
                return $sale->amount_paid;
            })
            ->addColumn('date',function ($sale){
                return date('F d, Y h:i:s A', strtotime($sale->created_at));
            })
            ->addColumn('action', function($sale){
                $action = '';
                if(auth()->user()->can('view invoices')) {
                    $action .= '<a href="#" class="btn btn-xs btn-outline-primary rounded view-invoice" id="'.$sale->id.'"><i class="fas fa-file-invoice"></i></a>&nbsp;';
                }

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
