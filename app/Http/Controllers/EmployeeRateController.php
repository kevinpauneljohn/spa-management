<?php

namespace App\Http\Controllers;


use App\Models\EmployeeTable;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleServices;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeRateController extends Controller
{
    protected $role;

    public function __construct(RoleServices $role)
    {
        $this->role = $role;
    }
    public function index()
    {
        return view('Payroll.rate');
    }

    public function setting(){

        $allrole = $this->role->getRole();
        $mapping = $allrole->map(function($map){
            return $map->id;
        });

     $employees = EmployeeTable::whereIn('user_id', $mapping)->get();

        return DataTables::of($employees)
        ->addColumn('name', function($employee){
            $user = User::where('id', $employee->user_id)->first();
            $firstname = $user->firstname;
            $lastname = $user->lastname;
            return $firstname.' '.$lastname;
        })
        ->addColumn('position', function($employee){
            $user = User::find($employee->user_id);
            $roleName = $user->getRoleNames()->toArray(); 
            return implode(', ', $roleName);
        })
        ->addColumn('rate', function($employee){
            return $employee->Monthly_Rate;
        })
        ->addColumn('action', function($employee){
            $html ="";
            if(auth()->user()->can('edit employee'))
            {
                $html .= '<button value="'.$employee->id.'" id="edit-rate-btn" data-id="'.$employee->id.'" data-target="#rateModal" data-toggle="modal" href="#" class="btn btn-sm btn-outline-primary edit-rate-btn"><i class="fa fa-edit"></i></button>&nbsp;';
            }
            return $html;
        })
        ->rawColumns(['name', 'action'])
        ->make(true);
    }
    public function editRate($id)
     {
        $employee = EmployeeTable::where('id',$id)->first();
        $collect = collect([
            "rate" => $employee->Monthly_Rate,
            "name" => $employee->user->firstname.' '. $employee->user->lastname,
        ]);
      
        return $collect;

    }
    public function updateRate(Request $request, $id)
    {
        $employee = EmployeeTable::where('id',$id)->first();
        $employee->Monthly_Rate = $request->newRate;
        $employee->update();
    }
}
