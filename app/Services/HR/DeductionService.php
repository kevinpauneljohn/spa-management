<?php

namespace App\Services\HR;

use App\Models\Deduction;
use App\Services\UserService;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class DeductionService extends PayrollService
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function add_new_deduction(array $data)
    {
        return Deduction::create($data);
    }

    public function get_deductions_datatable($owner_id, $payroll_id)
    {
        if($owner_id !== $this->userService->get_staff_owner()->id)
        {
            return response()->json("Unauthorized", 401);
        }
        $deductions = Deduction::where('payroll_id', $payroll_id)->get();
        return DataTables::of($deductions)
            ->editColumn('name', function($deduction){
                return ucwords($deduction->name);
            })
            ->editColumn('amount', function($deduction){
                return number_format($deduction->amount,2);
            })
            ->addColumn('action', function($deduction){
                $action = '';

                if(auth()->user()->can('edit deduction'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 mb-1 edit-deduction-button" id="'.$deduction->id.'" data-toggle="modal" data-target="#add-deduction-modal">Edit</button>';
                }
                if(auth()->user()->can('delete deduction'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger mr-1 mb-1 delete-deduction-button" id="'.$deduction->id.'">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
