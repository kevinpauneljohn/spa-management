<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
Use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Owners';
        return view('Owner.index',['title' => $title]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $mobile_number = $request['mobile_number'];
        $email = $request['email'];
        $username = $request['username'];
        $password = $request['password'];

        $validator = Validator::make($request->all(), [
            'firstname'     => 'required',
            'lastname'     => 'required',
            'mobile_number'     => 'required|unique:users,mobile_number',
            'email'     => 'required|unique:users,email',
            'username'     => 'required|unique:users,username',
            'password' => 'required|min:6,confirmed,required_with:password_confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        if($validator->passes())
        {
            $code = 201;
            $user = User::create([
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'mobile_number' => $mobile_number,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($password),
            ]);
            
            if ($user) {
                $user->assignRole('owner');
                $owner = Owner::create([
                    'user_id' => $user->id
                ]);
            }
            $response = [
                'status'   => true,
                'message'   => 'Owner Registration successfully saved.',
                'data'      => $user,
            ];    
            
            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $mobile_number = $request['mobile_number'];
        $email = $request['email'];
        $username = $request['username'];

        $validator = Validator::make($request->all(), [
            'firstname'    => 'required',
            'lastname'     => 'required',
            'mobile_number'=> 'required|unique:users,mobile_number,' . $user->id,
            'email'        => 'required|unique:users,email,' . $user->id,
            'username'     => 'required|unique:users,username,' . $user->id,
        ]);

        if($validator->passes())
        {
            $user->firstname = $firstname;
            $user->middlename = $middlename;
            $user->lastname = $lastname;
            $user->mobile_number = $mobile_number;
            $user->email = $email;
            $user->username = $username;
            if($user->isDirty()){
                $user->save();
                return response()->json(['status' => true, 'message' => 'Onwer information successfully updated.']);
            } else {
                return response()->json(['status' => false, 'message' => 'No changes has been made.']);
            } 
        }
        return response()->json($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $owner = Owner::where('user_id', $id)->first();
        if ($owner->delete()) {
            $user->delete();
        }
        return response()->json(['status' => true, 'message' => 'Owners information successfully deleted.']);
    }

    public function owner_lists()
    {
        $owners = User::role(['owner'])->get();
        return DataTables::of($owners)
            ->editColumn('created_at',function($owners){
                return $owners->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($owners){
                if(auth()->user()->can('view spa'))
                {
                    return '<a href="'.route('spa.overview',['id' => $owners->id]).'" title="View">'.$owners->fullname.'</a>&nbsp;';
                } else {
                    return $owners->fullname;
                }
            })
            ->addColumn('qty_of_spa',function ($owners){
                $count = $this->countSpa($owners->id);
                return $count;
            })
            ->addColumn('action', function($owners){
                $action = "";
                if(auth()->user()->can('view spa'))
                {
                    $action .= '<a href="'.route('spa.overview',['id' => $owners->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit owner'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-owner-btn" id="'.$owners->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete owner'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-owner-btn" id="'.$owners->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','fullname'])
            ->make(true);
    }

    public function countSpa($id)
    {
        $owner = Owner::where('user_id', $id)->first();
        $owner_id = $owner->id;
        $spa = Spa::where('owner_id', $owner_id)->get();
        $totalSpa = $spa->count();

        return $totalSpa;
    }
}
