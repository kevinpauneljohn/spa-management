<?php

namespace App\Services\HR;

use App\Models\Employee;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class EmployeeService
{
    private $user;
    public function createEmployee($employeeData, $role, $owner_id): array
    {
        if($this->saveUser($employeeData, $role))
        {
            if($this->saveUserAsEmployee($owner_id, $this->user->id))
            {
                return ['success' => true, 'message' => 'Employee added successfully'];
            }
            return ['success' => false, 'message' => 'User was created but not saved as employee'];
        }
        return ['success' => false, 'message' => 'Unable to create employee'];
    }
    public function saveUser($employeeData, $role): bool
    {
        $userCreated = User::create($employeeData);
        if($userCreated)
        {
            $userCreated->assignRole($role);
            $this->user = $userCreated;

            return true;
        }
        return false;
    }
    public function saveUserAsEmployee($owner_id, $user_id): bool
    {
        $employeeCreated = Employee::create([
            'owner_id' => $owner_id,
            'user_id' => $user_id
        ]);

        if($employeeCreated){
            return true;
        }
        return false;
    }

    public function getEmployees($owner_id)
    {
        $employees = Employee::where('owner_id', $owner_id)->get();
        return DataTables::of($employees)
            ->addColumn('name', function ($employee) {
                return '<a href="'.route('employees.show',['employee' => $employee->id]).'">' . $employee->user->fullname . '</a>';
            })
            ->addColumn('email', function ($employee) {
                return $employee->user->email;
            })
            ->addColumn('username', function ($employee) {
                return $employee->user->username;
            })
            ->addColumn('mobile_number', function ($employee) {
                return $employee->user->mobile_number;
            })
            ->addColumn('date_of_birth', function ($employee) {
                return $employee->user->date_of_birth;
            })
            ->addColumn('spa', function ($employee) {
                return !is_null($employee->user->spa_id) ? $employee->user->spa->name : '';
            })
            ->addColumn('role', function ($employee) {
                $roles = $employee->user->getRoleNames();
                $names = '';
                foreach ($roles as $role) {
                    $names .= '<span class="badge badge-info mr-1">' . $role . '</span> ';
                }
                return $names;
            })
            ->editColumn('updated_at', function ($employee) {
                return $employee->updated_at->format('Y-m-d h:i:s a');
            })
            ->addColumn('action', function($employee){
                $action = '';
                if(auth()->user()->can('view employee'))
                {
                    $action .= '<a href="'.route('employees.show',['employee' => $employee->id]).'" class="btn btn-sm btn-success mr-1 view-employee" id="'.$employee->id.'">View Profile</a>';
                }
                if(auth()->user()->can('edit employee'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 edit-employee" id="'.$employee->id.'">Edit</button>';
                }
                if(auth()->user()->can('delete employee'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger delete-employee" id="'.$employee->id.'">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['name','role','action'])
            ->make(true);
    }


    public function updateUserByEmployeeId($employee_id, $employeeData, $role): array
    {
        $user_id = $this->getUserIdByEmployee($employee_id);
        $user = User::findOrFail($user_id)->fill($employeeData);
        if($user->isDirty() || collect($user->getRoleNames())->doesntContain($role))
        {
            if($user->save())
            {
                $user->syncRoles($role);
                return ['success' => true, 'message' => 'Employee updated successfully'];
            }
        }
        return ['success' => false, 'message' => 'Unable to update employee'];
    }

    public function getUserIdByEmployee($employee_id)
    {
        return Employee::findOrFail($employee_id)->user->id;
    }

    public function updateUserRole()
    {

    }
}
