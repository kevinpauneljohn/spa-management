<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;

class ExpenseService
{
    public function expenses($expenses)
    {
        return DataTables::of($expenses)
            ->editColumn('created_at',function($expense){
//                return $expense->created_at->format('M d, Y');
                return $expense->created_at->format('m-d-y');
            })
            ->editColumn('title',function($expense){
                return ucfirst($expense->title);
            })
            ->editColumn('amount',function($expense){
                return number_format($expense->amount,2);
            })
            ->addColumn('action', function($expense){
                $action = "";
                if(auth()->user()->can('edit expenses'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-expense-btn" id="'.$expense->id.'" data-toggle="modal" data-target="#expense-modal"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete expenses'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-expense-btn" id="'.$expense->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
