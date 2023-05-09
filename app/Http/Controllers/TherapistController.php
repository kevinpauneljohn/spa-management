<?php

namespace App\Http\Controllers;

use App\Http\Requests\TherapistRequest;
use App\Models\Role;
use App\Services\TherapistService;
use App\Services\UserService;
use App\Models\User;
use App\Models\Therapist;

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

    public function index()
    {
        return view('Owner.therapist.all-therapist');
    }

    public function edit(Therapist $therapist)
    {
        return collect($therapist->user)->merge($therapist)->toArray();
    }

    public function store(TherapistRequest $request, UserService $userService)
    {
        if(Role::where('name','therapist')->count() === 0)
        {
            \Spatie\Permission\Models\Role::create(['name' => 'therapist']);
        }
        $user = $userService->create_user(collect($request->all())
            ->only(['spa_id','firstname','middlename','lastname','date_of_birth','email','mobile_number'])->toArray())
            ->assignRole('therapist');

        $therapist = collect($request->all())
            ->only(['spa_id','gender','certificate','commission_percentage','commission_flat','allowance','offer_type'])
            ->merge(['user_id' => $user->id])->toArray();

        if($user && Therapist::create($therapist))
        {
            return response()->json(['success' => true, 'message' => 'Therapist successfully added!']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred!'],500);
    }

    public function show(TherapistService $therapistService, $id)
    {
        return response()->json(['therapist' => $therapistService->get_therapist_by_id($id)]);
    }

    public function update(TherapistRequest $request, $id, UserService $userService, TherapistService $therapistService)
    {
        $therapist = Therapist::findOrFail($id);
        $user = User::findOrFail($therapist->user_id)->fill(collect($request->all())
            ->only(['firstname','middlename','lastname','date_of_birth','email','mobile_number'])->toArray());

        $therapist->fill(
            collect(
                $therapistService->offer_type_filter(collect($request->all())->toArray())
            )
            ->only(['spa_id','gender','certificate','commission_percentage','commission_flat','allowance','offer_type'])
            ->merge(['user_id' => $user->id])->toArray()
        );

        if($user->isClean() && $therapist->isClean()) return response()->json(['success' => false, 'message' => 'No changes!']);

        if($user->isDirty()) $user->save();
        if($therapist->isDirty()) $therapist->save();

        return response()->json(['success' => true, 'message' => 'Therapist successfully updated!']);

    }

    public function destroy($id, TherapistService $therapistService)
    {
        if($therapistService->delete_therapist($id)) return response()->json(['success' => true,'message' => 'Therapist moved to trash']);

        return response()->json(['success' => false,'message' => 'An error occured']);
    }

//    public function overview($id)
//    {
//        $spa = Spa::where('id', $id)->first();
//        $owner_id = $spa->owner_id;
//
//        $owner = Owner::where('id', $owner_id)->first();
//        $owners = User::role(['owner'])->where('id', $owner->user_id)->first();
//        $roles = $owners->getRoleNames()->first();
//
//        return view('Therapist.overview',compact('spa', 'owners', 'roles'));
//    }

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
