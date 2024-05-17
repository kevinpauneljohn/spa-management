<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExpenseService
{
    public $types = [
            [
                'name' => 'Cost Of Goods Sold',
                'description' => '',
            ],
            [
                'name' => 'Marketing Expense',
                'description' => '',
            ],
            [
                'name' => 'Advertising Expense',
                'description' => '',
            ],
            [
                'name' => 'Salaries Expense',
                'description' => '',
            ],
            [
                'name' => 'Rent Expense',
                'description' => '',
            ],
            [
                'name' => 'Extraordinary Expense',
                'description' => '',
            ],
            [
                'name' => 'Non-Operating Expense',
                'description' => '',
            ],
            [
                'name' => 'Non-Cash Expense',
                'description' => '',
            ],
            [
                'name' => 'Prepaid Expense',
                'description' => '',
            ],
            [
                'name' => 'Accrued Expense',
                'description' => '',
            ],
        ];
    public function expenses($expenses)
    {
        return DataTables::of($expenses)
            ->editColumn('date_expended',function($expense){
                return Carbon::parse($expense->date_expended)->format('M-d-Y');
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
            ->with([
                'total_expenses' => number_format($expenses->sum('amount'),2)
            ])
            ->make(true);
    }
}
