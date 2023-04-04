<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Spa;
use App\Models\Owner;
use App\Models\User;
class SpaController extends Controller
{
    public function lists($id)
    {
        $owner = Owner::where('user_id', $id)->first();
        $owner_id = $owner->id;

        $spa = Spa::where('owner_id', $owner_id)->get();
        return DataTables::of($spa)
            ->editColumn('created_at',function($spa){
                return $spa->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($spa){
                if(auth()->user()->can('view therapist'))
                {
                    return '<a href="'.route('therapist.overview',['id' => $spa->id]).'" title="View">'.$spa->name.'</a>&nbsp;';
                } else {
                    return $spa->name;
                }
            })
            ->addColumn('address',function ($spa){
                return $spa->address;
            })
            ->addColumn('action', function($spa){
                $action = "";
                if(auth()->user()->can('view service'))
                {
                    $action .= '<a href="'.route('service.overview',['id' => $spa->id]).'" class="btn btn-sm btn-outline-warning" title="Services"><i class="fas fa-hot-tub"></i></a>&nbsp;';
                }
                if(auth()->user()->can('view therapist'))
                {
                    $action .= '<a href="'.route('therapist.overview',['id' => $spa->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit spa'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-spa-btn" id="'.$spa->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete spa'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-spa-btn" id="'.$spa->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','name'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $id = $request['owner_id'];
        $owner = Owner::where('user_id', $id)->first();
        $owner_id = $owner->id;

        $name = $request['name'];
        $address = $request['address'];
        $number_of_rooms = $request['number_of_rooms'];
        $license = $request['license'] ? $request['license'] : null;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'number_of_rooms' => 'required'
        ]);

        if($validator->passes())
        {
            $code = 201;
            $spa = Spa::create([
                'owner_id' => $owner_id,
                'name' => $name,
                'address' => $address,
                'number_of_rooms' => $number_of_rooms,
                'license' => $license
            ]);
            
            $response = [
                'status'   => true,
                'message'   => 'Spa information successfully saved.',
                'data'      => $spa,
            ];    
            
            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    public function show($id)
    {
        $spa = Spa::findOrFail($id);
        return response()->json(['spa' => $spa]);
    }

    public function update(Request $request, $id)
    {
        $name = $request['name'];
        $address = $request['address'];
        $number_of_rooms = $request['number_of_rooms'];
        $license = $request['license'];

        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'address'     => 'required',
            'number_of_rooms'=> 'required',
        ]);

        if($validator->passes())
        {
            $spa = Spa::findOrFail($id);
            $spa->name = $name;
            $spa->address = $address;
            $spa->number_of_rooms = $number_of_rooms;
            $spa->license = $license;

            if($spa->isDirty()){
                $spa->save();
                return response()->json(['status' => true, 'message' => 'Spa information successfully updated.']);
            } else {
                return response()->json(['status' => false, 'message' => 'No changes made.']);
            } 
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $spa = Spa::findOrFail($id);

        $status = false;
        $message = 'Spa information could not be deleted.';
        if ($spa->delete()) {
            $status = true;
            $message = 'Spa information successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function overview($id)
    {
        $owners = User::role(['owner'])->where('id', $id)->first();
        $roles = $owners->getRoleNames()->first();

        return view('Spa.overview',compact('owners', 'roles'));
        // $spa = Spa::where('id', $id)->first();

        // return view('Spa.overview',compact('spa'));
    }
}
