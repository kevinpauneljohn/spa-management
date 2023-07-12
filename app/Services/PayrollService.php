<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;

class PayrollService
{
    public function payrollTable($payroll)
    {
        return DataTables::of($payroll)
            ->editColumn('created_at',function($spa){
                return $spa->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($spa){

                return '<a href="'.route('spa.show',['spa' => $spa->id]).'">'.ucwords($spa->name).'</a>';
            })
            ->addColumn('address',function ($spa){
                return $spa->address;
            })
            ->addColumn('action', function($spa){
                $action = "";

                return $action;
            })
            ->rawColumns(['action','name'])
            ->make(true);
    }
}
