<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;

class PayrollService
{
    public function payrollTable($spas)
    {
        return DataTables::of($spas)
            ->editColumn('created_at',function($spa){
                return $spa->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($spa){

                return '<a href="'.route('spa.show',['spa' => $spa->id]).'">'.ucwords($spa->name).'</a>';
            })
            ->addColumn('address',function ($spa){
                return '<i class="fa fa-globe mr-1 text-muted" aria-hidden="true"></i>'.$spa->address;
            })
            ->addColumn('action', function($spa){
                $action = "";
                if(auth()->user()->can('view payroll'))
                {
                    $action .= '<a href="'.route('payroll.show',['payroll' => $spa->id]).'" class="btn bg-gradient-orange text-white btn-sm">View Payroll</a>';
                }
                return $action;
            })
            ->rawColumns(['action','name','address'])
            ->make(true);
    }
}
