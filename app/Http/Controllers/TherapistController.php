<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Therapist;
Use Illuminate\Support\Facades\Hash;

class TherapistController extends Controller
{
    public function lists($id)
    {

        $therapist = Therapist::where('spa_id', $id)->get();
        return DataTables::of($therapist)
            ->editColumn('created_at',function($therapist){
                return $therapist->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($therapist){
                if(auth()->user()->can('view therapist'))
                {
                    return '<a href="'.route('spa.overview',['id' => $therapist->id]).'" title="View">'.$therapist->firstname.' '.$therapist->lastname.'</a>&nbsp;';
                } else {
                    return $therapist->firstname.' '.$therapist->lastname;
                }
            })
            ->editColumn('date_of_birth',function($therapist){
                return $therapist->created_at->format('F d, Y');
            })
            ->addColumn('mobile_number',function ($therapist){
                return $therapist->mobile_number;
            })
            ->addColumn('email',function ($therapist){
                return $therapist->email;
            })
            ->addColumn('gender',function ($therapist){
                return $therapist->gender;
            })
            ->addColumn('action', function($therapist){
                $action = "";
                if(auth()->user()->can('view therapist'))
                {
                    $action .= '<a href="'.route('spa.overview',['id' => $therapist->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit therapist'))
                {
                    $user = $this->getUserId($therapist->mobile_number, $therapist->email);
                    $user_id = '';
                    if (!empty($user)) {
                        $user_id = $user->id;
                    }
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-therapist-btn" id="'.$therapist->id.'" data-user_id="'.$user_id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete therapist'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-therapist-btn" id="'.$therapist->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','fullname'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $id = $request['spa_id'];
        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $date_of_birth = $request['date_of_birth'];
        $mobile_number = $request['mobile_number'];

        $email = $request['email'];
        if (empty($request['email'])) {
            $email = $firstname.'_'.$lastname.'_default_email@defaultemailspa.com';
        }

        $gender = $request['gender'];
        $certificate = $request['certificate'];
        $commission = $request['commission'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'commission' => 'required',
            'offer_type' => 'required',
        ]);

        if($validator->passes())
        {
            $code = 201;
            $isAllowed = false;
            $checkExistingUserMobile = User::where('mobile_number', $mobile_number)->first();
            $checkExistingUserEmail = User::where('email', $email)->first();

            if (empty($checkExistingUserMobile) && empty($checkExistingUserEmail)) {
                $isAllowed = true;
            }

            $status = false;
            if ($isAllowed) {
                $therapist = Therapist::create([
                    'spa_id' => $id,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'date_of_birth' => $date_of_birth,
                    'mobile_number' => $mobile_number,
                    'email' => $email,
                    'gender' => $gender,
                    'certificate' => $certificate,
                    'commission' => $commission,
                    'allowance' => $allowance,
                    'offer_type' => $offer_type
                ]);

                $user_data = [
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'mobile_number' => $mobile_number,
                    'email' => $email,
                    'username' => $firstname.'_'.$lastname.'_'.$therapist->id,
                    'password' => Hash::make('DefaultPassword'),
                ];

                $this->saveUser($user_data);
                $status = true;
                $message = 'Therapist information successfully saved.';
            } else {
                $message = 'Email or Mobile already exists in Users Data.';
            }

            $response = [
                'status'   => $status,
                'message'   => $message
            ];

            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    public function show($id)
    {
        $therapist = Therapist::findOrFail($id);
        return response()->json(['therapist' => $therapist]);
    }

    public function update(Request $request, $id)
    {
        $user_id = $request['user_id'];
        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $date_of_birth = $request['date_of_birth'];
        $mobile_number = $request['mobile_number'];

        $email = $request['email'];
        if (empty($request['email'])) {
            $email = $firstname.'_'.$lastname.'_default_email@defaultemailspa.com';
        }

        $gender = $request['gender'];
        $certificate = $request['certificate'];
        $commission = $request['commission'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'commission' => 'required',
            'offer_type' => 'required'
        ]);

        $user_data = [
            'id' => $user_id,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'mobile_number' => $mobile_number,
            'email' => $email,
        ];

        $isAllowed = false;
        $status = false;
        if($validator->passes())
        {
            $therapist = Therapist::findOrFail($id);
            $therapist->firstname = $firstname;
            $therapist->middlename = $middlename;
            $therapist->lastname = $lastname;
            $therapist->date_of_birth = $date_of_birth;
            $therapist->mobile_number = $mobile_number;
            $therapist->email = $email;
            $therapist->gender = $gender;
            $therapist->certificate = $certificate;
            $therapist->commission = $commission;
            $therapist->allowance = $allowance;
            $therapist->offer_type = $offer_type;

            $updateUser = $this->updateUser($user_data);
            if ($updateUser) {
                $isAllowed = true;
            }

            if ($isAllowed) {
                if($therapist->isDirty()){
                    $therapist->save();
                    $status = true;
                    $message = 'Therapist information successfully updated.';
                } else {
                    $status = false;
                    $message = 'No changes has been made.';
                }
            } else {
                $message = 'Email or Mobile already exists in Users Data.';
            }

            return response()->json(['status' => $status, 'message' => $message]);
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $therapist = Therapist::findOrFail($id);

        $status = false;
        $message = 'Therapist information could not be deleted.';
        if ($therapist->delete()) {
            $status = true;
            $message = 'Therapist information successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function overview($id)
    {
        $spa = Spa::where('id', $id)->first();
        $owner_id = $spa->owner_id;

        $owner = Owner::where('id', $owner_id)->first();
        $owners = User::role(['owner'])->where('id', $owner->user_id)->first();
        $roles = $owners->getRoleNames()->first();

        return view('Therapist.overview',compact('spa', 'owners', 'roles'));
    }

    public function getUserId($mobile, $email)
    {
        $user = User::where([
            'mobile_number' => $mobile,
            'email' => $email,
        ])->first();

        return $user;
    }

    public function saveUser($data)
    {
        $roleName = 'therapist';

        if(!$this->checkIfRoleNameExists($roleName))
        {
            \Spatie\Permission\Models\Role::create(['name' => $roleName]);
        }

        $user = User::create($data);
        if ($user) {
            $user->assignRole($roleName);
        }

        return true;
    }

    private function checkIfRoleNameExists($roleName): bool
    {
        return Role::where('name',$roleName)->count() > 0;
    }

    public function updateUser($data)
    {
        $user = User::findOrFail($data['id']);

        $firstname = $data['firstname'];
        $middlename = $data['middlename'];
        $lastname = $data['lastname'];
        $mobile_number = $data['mobile_number'];
        $email = $data['email'];

        $status = false;
        $user->firstname = $firstname;
        $user->middlename = $middlename;
        $user->lastname = $lastname;
        $user->mobile_number = $mobile_number;
        $user->email = $email;

        $isAllowed = false;
        $checkExistingUserMobile = User::where('mobile_number', $mobile_number)->first();
        $userMobileId = null;
        if (!empty($checkExistingUserMobile)) {
            $userMobileId = $checkExistingUserMobile->id;
        }

        $checkExistingUserEmail = User::where('email', $email)->first();
        $userEmailId = null;
        if (!empty($checkExistingUserEmail)) {
            $userEmailId = $checkExistingUserEmail->id;
        }

        if ($userMobileId == $data['id']) {
            if ($userEmailId == $data['id'] || $userEmailId == null) {
                $isAllowed = true;
            }
        }

        if ($isAllowed) {
            if($user->isDirty()){
                $user->save();
            }
            $status = true;
        }

        return $status;
    }
}
