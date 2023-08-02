<?php

namespace App\Services\PointOfSales\Sales;

use Yajra\DataTables\Facades\DataTables;

class SalesService
{
    public function salesList($sales)
    {
        return DataTables::of($sales)
            ->editColumn('created_at',function($sale){
                return $sale->created_at->format('d-M-Y g:i a');
            })
            ->editColumn('invoice_number',function($sale){
                return '<a href="'.route('pos.add.transaction',['spa' => $sale->spa_id,'sale' => $sale->id]).'" class="text-primary">#'.$sale->invoice_number.'</a>';
            })
            ->addColumn('payment_required',function($sale){
                $status = "";
                $completed = $sale->transactions()->where('end_time','<',now())->count();

                if($sale->transactions->count() === 0)
                {
                    $status .= "No Transaction,";
                }

                if($completed === $sale->transactions->count() && $sale->payment_status !== 'completed')
                {
                    $status .= " Payment Required";
                }

                return $status;
            })
            ->addColumn('completed',function($sale){
                $completed = $sale->transactions()->where('end_time','<',now())->count();
                return $completed.'/'.$sale->transactions->count();
            })
            ->addColumn('total_amount',function($sale){
                return '<span class="text-info">'.number_format($sale->transactions->sum('amount'),2).'</span>';
            })
            ->addColumn('rooms',function($sale){
                $rooms = '';
                foreach (collect($sale->transactions)->pluck('room_id') as $room)
                {
                    $rooms .= '<span class="badge badge-info mr-1 mb-1">'.$room.'</span>';
                }
                return '<span class="text-info">'.$rooms.'</span>';
            })
            ->editColumn('payment_status',function($sale){
                $status = '';
                if($sale->payment_status === 'pending')
                {
                    $status = '<span class="badge badge-danger">'.$sale->payment_status.'</span>';
                }
                else{
                    $status = '<span class="badge badge-success">'.$sale->payment_status.'</span>';
                }
                return $status;
            })
            ->addColumn('action',function($sale){
                $action = "";
//                if(auth()->user()->can('access pos'))
//                {
                $action .= '<a href="'.route('pos.add.transaction',['spa' => $sale->spa_id,'sale' => $sale->id]).'" class="btn btn-sm btn-outline-success m-1" id="'.$sale->id.'" title="View"><i class="fas fa-eye"></i></a>';
//                }
                return $action;
            })
            ->setRowAttr([
                'title' => function($sale) {
                    $status = "";
                    $completed = $sale->transactions()->where('end_time','<',now())->count();
                    if($completed === $sale->transactions->count())
                    {
                        $status .= "Payment Required";
                    }
                    if($sale->transactions->count() === 0)
                    {
                        $status .= " & No Transaction";
                    }

                    return $status;
                },
                'class' => function($sale){
                    $classes = "";
                    $completed = $sale->transactions()->where('end_time','<',now())->count();
                    if($completed === $sale->transactions->count() && $sale->payment_status !== 'completed')
                    {
                        $classes .= "payment-required";
                    }
                    if($sale->transactions->count() === 0)
                    {
                        $classes .= " no-transaction";
                    }
                    return $classes;
                }
            ])
            ->rawColumns(['total_amount','action','invoice_number','payment_status','rooms'])
            ->with([
                'transactions' => $transactions = collect($sales->pluck('transactions')),
                'total_sales' => $sales->count(),
                'pending_sales' => $sales->where('payment_status','pending')->count(),
                'completed_sales' => $sales->where('payment_status','completed')->count(),
                'total_expected_amount' => number_format($sales->pluck('transactions')->flatten()->sum('amount'),2),
                'total_clients' => $sales->pluck('transactions')->flatten()->count(),
                'total_amount_paid' => number_format($sales->where('payment_status','completed')->sum('amount_paid'),2)
            ])
            ->make(true);
    }
}
