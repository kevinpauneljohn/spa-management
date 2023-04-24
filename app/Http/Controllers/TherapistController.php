<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\TherapistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Therapist;
Use Illuminate\Support\Facades\Hash;

class TherapistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check.if.user.is.owner'])->only(['lists','therapist_profile']);
    }
    public function lists(TherapistService $therapistService, $spa_id)
    {
        return $therapistService->all_therapist_thru_spa(Therapist::where('spa_id', $spa_id)->get());
    }

    private function custom_offer_type_validation($request): string
    {
        if($request['offer_type'] === 'percentage_only' && $request['commission_percentage'] === null)
        {
            $request['offer_type'] = 'commission percentage field is required';
        }
        else if($request['offer_type'] === 'percentage_plus_allowance' && $request['commission_percentage'] === null && $request['allowance'] === null)
        {
            $request['offer_type'] = 'commission percentage and allowance field are required';
        }
        else if($request['offer_type'] === 'amount_only' && $request['commission_flat'] === null)
        {
            $request['offer_type'] = 'commission amount is required';
        }
        else if($request['offer_type'] === 'amount_plus_allowance' && $request['commission_flat'] === null && $request['allowance'] === null)
        {
            $request['offer_type'] = 'commission amount and allowance field are required';
        }
        return $request['offer_type'];
    }

    public function store(Request $request)
    {
//        return $this->custom_offer_type_validation($request->all());
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
        $commission_percentage = $request['commission_percentage'];
        $commission_flat = $request['commission_flat'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

        $request['offer_type'] = $this->custom_offer_type_validation($request->all());
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'offer_type' => [
                'required',
                function($attribute, $value, $fail){
                    if ($value !== 'percentage_only' &&
                        $value !== 'percentage_plus_allowance' &&
                        $value !== 'amount_only' &&
                        $value !== 'amount_plus_allowance') {
                        $fail($value);
                    }

                }
            ],
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
                    'commission_percentage' => $commission_percentage,
                    'commission_flat' => $commission_flat,
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

    public function show(TherapistService $therapistService, $id)
    {
        return response()->json(['therapist' => $therapistService->get_therapist_by_id($id)]);
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
//        $commission_percentage = $request['commission_percentage'];
//        $commission_flat = $request['commission_flat'];
//        $allowance = $request['allowance'];
//        $offer_type = $request['offer_type'];

        $request['offer_type'] = $this->custom_offer_type_validation($request->all());
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'offer_type' => [
                'required',
                function($attribute, $value, $fail){
                    if ($value !== 'percentage_only' &&
                        $value !== 'percentage_plus_allowance' &&
                        $value !== 'amount_only' &&
                        $value !== 'amount_plus_allowance') {
                        $fail($value);
                    }

                }
            ],
        ]);

        //this will make sure the proper offer will be given
        if($request['offer_type'] === 'percentage_only')
        {
            $request['commission_flat'] = null;
            $request['allowance'] = null;
        }
        else if($request['offer_type'] === 'percentage_plus_allowance')
        {
            $request['commission_flat'] = null;
        }
        else if($request['offer_type'] === 'amount_only')
        {
            $request['commission_percentage'] = null;
            $request['allowance'] = null;
        }
        else
        {
            $request['commission_percentage'] = null;
        }

        $commission_percentage = $request['commission_percentage'];
        $commission_flat = $request['commission_flat'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

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
            $therapist->commission_percentage = $commission_percentage;
            $therapist->commission_flat = $commission_flat;
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

    public function therapist_profile(TherapistService $therapistService, $therapist_id)
    {
        return view('Owner.therapist.index')
            ->with([
                'title' => 'Therapist Profile',
                'therapist' => $therapist = $therapistService->get_therapist_by_id($therapist_id),
                'owner' => $therapist->spa->owner->user,
                'spa' => $therapist->spa
            ]);
    }
}
