<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return view('Role.index')->with([
            'roles' => Role::where('name','!=','super admin')->get()
        ]);
    }

    public function lists()
    {
        $roles = Role::where('name','!=','super admin')->get();

        return DataTables::of($roles)
            ->addColumn('name',function($roles){
                return ucfirst($roles->name);
            })
            ->addColumn('action', function ($roles)
            {
                $action = "";
                if(auth()->user()->can('edit role'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-primary edit-role-btn" id="'.$roles->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete role'))
                {
                $action .= '<a href="#" class="btn btn-xs btn-danger delete-role-btn" id="'.$roles->id.'" data-name="'.strtolower($roles->name).'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['role','action'])
            ->make(true);
    }

    public function show($id)
    {
        $roles = Role::findOrFail($id);
        return response()->json(['role' => $roles]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|unique:roles,name',
        ]);

        if($validator->passes())
        {
            \Spatie\Permission\Models\Role::create(['name' => strtolower($request->name)]);

            return response()->json(['status' => true, 'message' => 'New Roles successfully saved!']);
        }

        return response()->json($validator->errors());
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'     => ['required','unique:roles,name']
        ],[
            'name.required'  => 'Role name is required',
            'name.unique'  => 'Role name was already taken'
        ]);

        if($validator->passes())
        {
            $role = Role::findOrFail($id);
            $role->name = $request->name;

            if($role->isDirty()){
                $role->save();
                return response()->json(['status' => true, 'message' => 'Roles has been successfully udpated!']);
            } else {
                return response()->json(['status' => false, 'message' => 'No changes made.']);
            }
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if($role->delete())
        {
            return response()->json(['status' => true, 'message' => 'Roles has been successfully deleted!']);
        }
        return response()->json(['status' => false, 'message' => 'Roles could not be deleted!']);
    }

    public function getRoleList()
    {
        if(auth()->user()->hasRole(['owner'])) {
//            $role_exclude = ['super admin', 'owner'];
//            $role = Role::whereNotIn('name', $role_exclude)->orderBy('name' , 'ASC')->pluck('id', 'name');
            $role_included = ['front desk', 'hr manager'];
            $role = Role::whereIn('name', $role_included)->orderBy('name' , 'ASC')->pluck('id', 'name');
        } else {
            $role = Role::pluck('id, name');
        }

        return $role;
    }
}
