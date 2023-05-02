<?php

namespace App\Http\Controllers;

use App\Services\OwnerServices;
use App\Services\SpaService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Spa;
use App\Models\Owner;
use App\Models\User;
class SpaController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.if.user.is.owner')->only(['my_spas','get_owner_spas']);
        $this->middleware(['check.if.user.is.owner','check.if.spa.belongs.to.owner'])->only(['show']);
    }
    public function lists(SpaService $spaService, $id)
    {
        $owner = Owner::where('user_id', $id)->first();
        $spa = $owner->spas;
        return $spaService->spas($spa);
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

    public function show($id, OwnerServices $ownerServices)
    {
        $spa = Spa::findorFail($id);
        $owner = $ownerServices->getOwnerBySpaID($id);
        $range = range(5, 300, 5);
        return view('Spa.profile',  compact('spa','owner','range'));
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

    public function get_owner_spas(SpaService $spaService)
    {
        return $spaService->spas(auth()->user()->owner->spas);
    }
    public function my_spas()
    {
//        return auth()->user()->owner->spas;
        return view('Owner.spa.index');
    }

    public function getSpaList(SpaService $spaService)
    {
        return $spaService->get_spa_lists(auth()->user()->owner->id);
    }
}
