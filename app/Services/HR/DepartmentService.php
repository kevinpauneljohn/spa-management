<?php

namespace App\Services\HR;

use App\Models\Department;
use App\Models\User;
use App\Services\UserService;
use Yajra\DataTables\Facades\DataTables;

class DepartmentService
{
    public $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function departments()
    {
        $owner_id = $this->userService->get_staff_owner()->id;
        $departments = Department::where('owner_id',$owner_id)->get();
        return DataTables::of($departments)
            ->editColumn('updated_at', function ($department) {
                return $department->updated_at->format('Y-m-d h:i:s a');
            })
            ->editColumn('user_id', function ($department) {
                return ucwords(strtolower(User::findOrFail($department->user_id)->fullname));
            })
            ->addColumn('action', function($department){
                $action = '';
                if(auth()->user()->can('edit department'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-primary mr-1 edit-department" id="'.$department->id.'">Edit</button>';
                }
                if(auth()->user()->can('delete department'))
                {
                    $action .= '<button type="button" class="btn btn-sm btn-danger delete-department" id="'.$department->id.'">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveDepartment($owner_id, $name, $user_id)
    {
        return Department::create([
            'owner_id' => $owner_id,
            'name' => $name,
            'user_id' => $user_id
        ]);
    }

    public function updateDepartment($department_id, $owner_id, $name, $user_id): array
    {
        $department = Department::findOrFail($department_id);
        $department->owner_id = $owner_id;
        $department->name = $name;
        $department->user_id = $user_id;

        if($department->isDirty())
        {
            if($department->save()){
                return ['success' => true, 'message' => 'Department has been updated'];

            }
            return ['success' => false, 'message' => 'An error occured while updating the department'];
        }
        return ['success' => false, 'message' => 'No changes have been made'];
    }
}
