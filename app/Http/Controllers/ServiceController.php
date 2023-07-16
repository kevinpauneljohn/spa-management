<?php

namespace App\Http\Controllers;

use App\Services\SpaService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Service;

class ServiceController extends Controller
{
    public function lists(SpaService $spaService, $id)
    {
        return $spaService->spa_services($id);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'category' => 'required',
            'commission_reference_amount' => 'required',
            'price_per_plus_time' => 'required'
        ]);

        if($validator->passes())
        {
            $service = Service::create([
                'spa_id' => $request->spa_id,
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'price' => $request->price,
                'category' => $request->category,
                'multiple_masseur' => $request->multiple_masseur === 'on',
                'commission_reference_amount' => $request->commission_reference_amount,
                'price_per_plus_time' => $request->price_per_plus_time
            ]);

            return response()->json([
                'status'   => true,
                'message'   => 'Services information successfully saved.',
                'data'      => $service,
            ]);
        }
        return response()->json($validator->errors());
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'category' => 'required',
            'price_per_plus_time' => 'required',
            'commission_reference_amount' => 'required'
        ]);

        if($validator->passes())
        {
//            $multipleMasseur = $request->multiple_masseur !== null ? 1 : 0;
            $service->name = $request->name;
            $service->description = $request->description;
            $service->multiple_masseur = $request->multiple_masseur !== null ? 1 : 0;
            $service->duration = $request->duration;
            $service->price = $request->price;
            $service->category = $request->category;
            $service->commission_reference_amount = $request->commission_reference_amount;
            $service->price_per_plus_time = $request->price_per_plus_time;

            if($service->isDirty()){
                $service->save();

                return response()->json(['status' => true, 'message' => 'Services information successfully updated.']);
            } else {
                return response()->json(['status' => false, 'message' => 'No changes made.']);
            }
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $status = false;
        $message = 'Services information could not be deleted.';
        if ($service->delete()) {
            $status = true;
            $message = 'Services information successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function overview($id)
    {
        $spa = Spa::where('id', $id)->first();
        $owner_id = $spa->owner_id;

        $owner = Owner::where('id', $owner_id)->first();
        $owners = User::role(['owner'])->where('id', $owner->user_id)->first();

        $range = range(5, 300, 5);
        return view('Service.overview',compact('spa', 'owners', 'range'));
    }

    public function durationRange()
    {
        $range = range(15, 300, 15);

        $plus_time = [];
        foreach ($range as $ranges) {
            $hrs = floor($ranges/60);
            $mins = $ranges%60;

            if ($hrs > 0) {
                $hours = $hrs.' hrs';
            } else {
                $hours = $hrs.' hr';
            }

            $value = '';
            if ($hrs == 0) {
                $value = $mins.' mins';
            } else if ($mins == 0) {
                $value = $hours;
            } else {
                $value = $hours.' & '.$mins.' mins';
            }

            $plus_time [$ranges] = $value;
        }

        return response()->json(['range' => $plus_time]);
    }

    public function servicePricing($id, $spa_id)
    {
        $service = Service::where(['id' => $id, 'spa_id' => $spa_id])->first();

        return $service->price;
    }

    public function servicePricingPlusTime($id, $spa_id, $selected_id)
    {
        $service = Service::where(['id' => $id, 'spa_id' => $spa_id])->first();

        $total = ($selected_id * $service->price_per_plus_time) / 15;
        $total_amount = 0;
        if ($total > 0) {
            $total_amount = $total;
        }

        return $total_amount;
    }
}
