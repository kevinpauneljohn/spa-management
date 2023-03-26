<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Owner;
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
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
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
                'success'   => true,
                'message'   => 'Owner Registration successfully saved.',
                'data'      => $user,
            ];            
        } else {
            $code = 201;
            $validator_error = $validator->errors()->toArray();
            if (isset($validator_error['email'])) {
                $message = 'Email has already been taken.';
            } else if (isset($validator_error['username'])) {
                $message = 'Username has already been taken.';
            } else if (isset($validator_error['mobile_number'])) {
                $message = 'Mobile Number has already been taken.';
            } else if (isset($validator_error['password'])) {
                $message = 'The password must be at least 6 characters.';
            } else if (isset($validator_error['password_confirmation'])) {
                $message = 'The password confirmation must be at least 6 characters.';
            }

            $response = [
                'success'   => false,
                'message'   => $message,
            ];
        }

        return response($response, $code);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function owner_lists()
    {
        $owners = User::role(['super admin', 'owner'])->get();
        return DataTables::of($owners)
            ->editColumn('created_at',function($owners){
                return $owners->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($owners){
                return $owners->fullname;
            })
            ->addColumn('qty_of_spa',function ($owners){
                return "";
            })
            ->addColumn('action', function($owners){
                $action = "";
                $action .= '<div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item view-reserved-unit" href="#" id="'.$owners->id.'" title="View Details" data-toggle="modal" data-target="#view-sales-details">View</a>
                        <a class="dropdown-item edit-owner-btn" href="#" id="'.$owners->id.'" title="Edit Details">Edit</a>
                        <a class="dropdown-item view-requirements" href="#" id="'.$owners->id.'" title="View Requirements" data-toggle="modal" data-target="#view-requirements">Manage Requirements</a>
                    </div>
                </div>';

                return $action;
            })
            ->rawColumns(['action','status','total_contract_price','requirements','payments'])
            ->make(true);
    }
}
