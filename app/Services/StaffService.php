<?php

namespace App\Services;

use App\Events\EmployeeCreated;
use App\Events\UserCreated;
use App\Models\EmployeeTable;
use App\Models\Shift;
use App\Models\User;
use App\Models\Spa;
use App\Models\Therapist;
use Yajra\DataTables\Facades\DataTables;
Use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffService
{
    public function staffs($id)
    {
        $spa = Spa::where('owner_id', $id)->get();

        $data = [];
        if (!empty($spa)) {
            foreach ($spa as $list) {
                $data [] = $list->id;
            }
        }

        $staff = User::whereIn('spa_id', $data)->get();

        return DataTables::of($staff)
            ->editColumn('created_at',function($staff){
                return date('F d, Y', strtotime($staff->created_at));
            })
            ->editColumn('spa',function ($staff){

                return $this->getSpaNameService($staff->spa_id);
            })
            ->editColumn('name',function ($staff){

                return ucfirst($staff->firstname).' '.ucfirst($staff->lastname);
            })
            ->editColumn('email',function ($staff){

                return '<a href="mailto:'.$staff->email.'">'.$staff->email.'</a>';
            })
            ->editColumn('mobile',function ($staff){

                return '<a href="tel:'.$staff->mobile_number.'">'.$staff->mobile_number.'</a>';
            })
            ->editColumn('position',function ($staff){

                $roles = $staff->getRoleNames()->first();
                return ucfirst($roles);
            })
            ->addColumn('action', function($staff){
                $action = "";
                // if (auth()->user()->can('view staff')) {
                //     $action .= '<a href="'.route('spa.show',['id' => $staff->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                // }

                if (auth()->user()->can('edit staff')) {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-staff-btn" id="'.$staff->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }

                if (auth()->user()->can('delete staff')) {
                    // $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-staff-btn" id="'.$staff->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }

                return $action;
            })
            ->rawColumns(['action','name','email','mobile'])
            ->make(true);
    }

    public function getSpaNameService($id)
    {
        $spa = Spa::findOrFail($id);

        return ucfirst($spa->name);
    }

    public function create($data)
    {
        if ($data['role'] == 'therapist') {
            $validator = Validator::make($data->all(), [
                'firstname'     => 'required',
                'lastname'     => 'required',
                'mobile_number'     => 'unique:users,mobile_number',
                'email'     => 'unique:users,email',
                'username'     => 'unique:users,username',
            ]);
        } else {
            $validator = Validator::make($data->all(), [
                'firstname'     => 'required',
                'lastname'     => 'required',
                'mobile_number'     => 'required|unique:users,mobile_number',
                'email'     => 'required|unique:users,email',
                'username'     => 'required|unique:users,username',
                'password' => 'required|min:6,confirmed,required_with:password_confirmed',
                'password_confirmation' => 'required|min:6'
            ]);
        }

        if($validator->passes())
        {
            $code = 422;
            $status = false;
            $message = 'Staff could not be saved.';
            if (!empty($data)) {
                $user = User::create([
                    'spa_id' => $data['spa'],
                    'firstname' => $data['firstname'],
                    'middlename' => $data['middlename'],
                    'lastname' => $data['lastname'],
                    'mobile_number' => $data['mobile_number'],
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                ]);

                if ($user) {
                    $user->assignRole($data['role']);

                    $therapist_data = [];
                    if ($data['role'] == 'therapist') {
                        $commission_percentage = 0;
                        if ($data['offer_type'] == 'percentage_only' || $data['offer_type'] == 'percentage_plus_allowance') {
                            $commission_percentage = $data['commission'];
                        }

                        $commission_flat = 0;
                        if ($data['offer_type'] == 'amount_only' || $data['offer_type'] == 'amount_plus_allowance') {
                            $commission_flat = $data['commission'];
                        }

                        $therapist_data = [
                            'spa_id' => $data['spa'],
                            'user_id' => $user->id,
                            'gender' => $data['gender'],
                            'certificate' => $data['certificate'],
                            'commission_percentage' => $commission_percentage,
                            'commission_flat' => $commission_flat,
                            'allowance' => $data['allowance'],
                            'offer_type' => $data['offer_type']
                        ];

                        $this->createTherapist($therapist_data);
                    }

                    $code = 200;
                    $status = true;
                    $message = 'Staff Registration successfully saved.';
                }
            }

            $response = [
                'status'   => $status,
                'message'   => $message,
                'data'      => $user,
            ];

            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    public function createTherapist($data)
    {
        if (!empty($data)) {
            $therapist = Therapist::create([
                'spa_id' => $data['spa_id'],
                'user_id' => $data['user_id'],
                'gender' => $data['gender'],
                'certificate' => $data['certificate'],
                'commission_percentage' => $data['commission_percentage'],
                'commission_flat' => $data['commission_flat'],
                'allowance' => $data['allowance'],
                'offer_type' => $data['offer_type']
            ]);

            if ($therapist) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $roles = $user->getRoleNames()->first();

        $isTherapist = [];
        if ($user->hasRole('therapist')) {
            $isTherapist = $this->getUserTherapistData($user->spa_id, $user->id);
        }

        $response = [
            'staff' => $user,
            'role' => $user->getRoleNames()->first(),
            'is_therapist' => $isTherapist
        ];

        return response($response);
    }

    public function getUserTherapistData($spa_id, $user_id)
    {
        $therapist = Therapist::where([
            'spa_id' => $spa_id,
            'user_id' => $user_id
        ])->first();

        $data = [];
        if (!empty($therapist)) {
            $data = $therapist;
        }

        return $data;
    }

    public function edit($data, $id)
    {
        $user = User::findOrFail($id);
        if ($data['role'] == 'therapist') {
            $validator = Validator::make($data->all(), [
                'firstname'    => 'required',
                'lastname'     => 'required',
                'mobile_number'=> 'unique:users,mobile_number,' . $user->id,
                'email'        => 'unique:users,email,' . $user->id,
                'username'     => 'unique:users,username,' . $user->id,
            ]);
        } else {
            $validator = Validator::make($data->all(), [
                'firstname'    => 'required',
                'lastname'     => 'required',
                'mobile_number'=> 'required|unique:users,mobile_number,' . $user->id,
                'email'        => 'required|unique:users,email,' . $user->id,
                'username'     => 'required|unique:users,username,' . $user->id,
            ]);
        }

        if($validator->passes())
        {
            $user->spa_id = $data['spa'];
            $user->firstname = $data['firstname'];
            $user->middlename = $data['middlename'];
            $user->lastname = $data['lastname'];
            $user->mobile_number = $data['mobile_number'];
            $user->email = $data['email'];
            $user->username = $data['username'];

            $user->save();
            if ($user->roles()->detach()) {
                $user->assignRole($data['role']);
            }

            $therapist_data = [];
            if ($data['role'] == 'therapist') {
                $commission_percentage = 0;
                if ($data['offer_type'] == 'percentage_only' || $data['offer_type'] == 'percentage_plus_allowance') {
                    $commission_percentage = $data['commission'];
                }

                $commission_flat = 0;
                if ($data['offer_type'] == 'amount_only' || $data['offer_type'] == 'amount_plus_allowance') {
                    $commission_flat = $data['commission'];
                }

                if (!empty($data['therapist_id'])) {
                    $therapist_data = [
                        'spa_id' => $data['spa'],
                        'gender' => $data['gender'],
                        'certificate' => $data['certificate'],
                        'commission_percentage' => $commission_percentage,
                        'commission_flat' => $commission_flat,
                        'allowance' => $data['allowance'],
                        'offer_type' => $data['offer_type']
                    ];

                    $this->updateTherapist($data['therapist_id'], $therapist_data);
                } else {
                    $therapist_data = [
                        'spa_id' => $data['spa'],
                        'user_id' => $user->id,
                        'gender' => $data['gender'],
                        'certificate' => $data['certificate'],
                        'commission_percentage' => $commission_percentage,
                        'commission_flat' => $commission_flat,
                        'allowance' => $data['allowance'],
                        'offer_type' => $data['offer_type']
                    ];

                    $check_therapist = Therapist::where('user_id', $user->id)->withTrashed()->first();
                    if (!empty($check_therapist)) {
                        $restore_therapist = Therapist::withTrashed()->find($check_therapist->id)->restore();
                        if ($restore_therapist) {
                            $this->updateTherapist($check_therapist->id, $therapist_data);
                        }
                    } else {
                        $this->createTherapist($therapist_data);
                    }

                }
            } else {
                if (!empty($data['therapist_id'])) {
                    $therapist_delete = Therapist::findOrFail($data['therapist_id']);

                    $therapist_delete->delete();
                }
            }
            return response()->json(['status' => true, 'message' => 'Staff information successfully updated.']);
        }
        return response()->json($validator->errors());
    }

    public function updateTherapist($id, $data)
    {
        $therapist = Therapist::findOrFail($id);

        $therapist->spa_id = $data['spa_id'];
        $therapist->gender = $data['gender'];
        $therapist->certificate = $data['certificate'];
        $therapist->commission_percentage = $data['commission_percentage'];
        $therapist->commission_flat = $data['commission_flat'];
        $therapist->allowance = $data['allowance'];
        $therapist->offer_type = $data['offer_type'];

        if ($therapist->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()->json(['status' => true, 'message' => 'Staff information successfully deleted.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Staff information could not be deleted.']);
        }
    }
}
