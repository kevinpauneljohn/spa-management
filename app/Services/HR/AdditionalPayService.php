<?php

namespace App\Services\HR;

use App\Models\AdditionalPay;
use Yajra\DataTables\DataTables;

class AdditionalPayService
{
    public function save_additional_pay(array $data)
    {
        return AdditionalPay::create($data);
    }

    public function get_additional_pay_datatable($payroll_id)
    {
        $additionalPays = AdditionalPay::where('payroll_id',$payroll_id)->get();
        return DataTables::of($additionalPays)
            ->editColumn('name', function($additionalPay){
                return ucwords($additionalPay->name);
            })
            ->editColumn('amount', function($additionalPay){
                return number_format($additionalPay->amount,2);
            })
            ->addColumn('action', function($additionalPay){
                $action = '';

                if(auth()->user()->can('edit deduction'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 mb-1 edit-additional-pay-button" id="'.$additionalPay->id.'" data-toggle="modal" data-target="#add-additional-pay-modal">Edit</button>';
                }
                if(auth()->user()->can('delete deduction'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger mr-1 mb-1 delete-additional-pay-button" id="'.$additionalPay->id.'">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
