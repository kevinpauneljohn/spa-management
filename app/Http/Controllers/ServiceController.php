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
        $id = $request['spa_id'];
        $name = $request['name'];
        $description = $request['description'];
        $duration = $request['duration'];
        $price = $request['price'];
        $category = $request['category'];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'category' => 'required'
        ]);

        if($validator->passes())
        {
            $code = 201;
            $service = Service::create([
                'spa_id' => $id,
                'name' => $name,
                'description' => $description,
                'duration' => $duration,
                'price' => $price,
                'category' => $category
            ]);

            $response = [
                'status'   => true,
                'message'   => 'Services information successfully saved.',
                'data'      => $service,
            ];

            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);

        $range = range(5, 300, 5);
        $data = [];
        foreach ($range as $ranges) {
            $data [$ranges] = $ranges;
        }
        return response()->json(['service' => $service, 'range' => $data]);
    }

    public function update(Request $request, $id)
    {
        $name = $request['name'];
        $description = $request['description'];
        $duration = $request['duration'];
        $price = $request['price'];
        $category = $request['category'];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'category' => 'required'
        ]);

        if($validator->passes())
        {
            $service = Service::findOrFail($id);
            $service->name = $name;
            $service->description = $description;
            $service->duration = $duration;
            $service->price = $price;
            $service->category = $category;

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
        $range = range(5, 300, 5);

        $data = [];
        foreach ($range as $ranges) {
            $data [$ranges] = $ranges;
        }
        return response()->json(['range' => $data]);
    }
}
