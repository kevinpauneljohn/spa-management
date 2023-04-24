<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('Permission.index')->with([
            'roles' => Role::where('name','!=','super admin')->get()
        ]);
    }

    public function lists()
    {
        $permissions = Permission::all();

        return DataTables::of($permissions)
            ->addColumn('name',function($permission){
                return ucfirst($permission->name);
            })
            ->addColumn('role',function($permission){

                $role_permissions = \Spatie\Permission\Models\Permission::whereName($permission->name)->first()->roles;
                $role = "";
                foreach ($role_permissions as $roles)
                {
                    $role .= '<span class="badge badge-info right role-badge">'.ucfirst($roles->name).'</span>';
                }

                return $role;
            })
            ->addColumn('action', function ($permission)
            {
                $action = "";
                if(auth()->user()->can('edit permission'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-primary edit-permission-btn" id="'.$permission->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete permission'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-danger delete-permission-btn" id="'.$permission->id.'" data-name="'.strtolower($permission->name).'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['role','action'])
            ->make(true);
    }

    public function getPermissionRoles(Request $request)
    {
        $roles = \Spatie\Permission\Models\Permission::whereName($request->name)->first()->roles->pluck('name');
        return $roles;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission'    => 'required|unique:permissions,name',
        ]);

        if($validator->passes())
        {
            $permission = \Spatie\Permission\Models\Permission::create(['name' => strtolower($request->permission)]);
            if($request->roles !== null)
            {
                $permission->assignRole($request->roles);
            }

            return response()->json(['status' => true, 'message' => 'Permission successfully added!']);
        }
        return response()->json($validator->errors());
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $permission = \Spatie\Permission\Models\Permission::findById($id);
        $validator = Validator::make($request->all(),[
            'edit_permission'   => 'required|unique:permissions,name,' . $permission->id,
        ]);

        if($validator->passes())
        {
            $permission->name = strtolower($request->edit_permission);
            if($permission->isDirty()){
                $permission->save();    
                $message = 'Permission successfully updated!';
            } else {
                $message = 'Assigned Roles successfully updated.';
            } 

            $roles = \Spatie\Permission\Models\Permission::whereName($permission->name)->first()->roles->pluck('name');
            if (!empty($roles)) {
                foreach ($roles as $role)
                {
                    $permission->removeRole($role);
                }
            }

            $permission->assignRole($request->edit_roles);

            return response()->json(['status' => true, 'message' => $message]);
        }
        return response()->json($validator->errors());

    }

    public function destroy($id, $name)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->delete()) {
            return response()->json(['status' => true, 'message' => 'Permission has been successfully deleted.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Permission could not be deleted.']);
        }
    }
}
