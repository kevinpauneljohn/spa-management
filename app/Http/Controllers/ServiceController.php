<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Service;

class ServiceController extends Controller
{
    public function lists($id)
    {
        $service = Service::where('spa_id', $id)->get();
        return DataTables::of($service)
            ->editColumn('created_at',function($service){
                return $service->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($service){
                if(auth()->user()->can('view service'))
                {
                    return '<a href="'.route('spa.overview',['id' => $service->id]).'" title="View">'.ucfirst($service->name).'</a>&nbsp;';
                } else {
                    return ucfirst($service->name);
                }
            })
            ->editColumn('description',function($service){
                return $service->description;
            })
            ->addColumn('duration',function ($service){
                return $service->duration.' mins.';
            })
            ->addColumn('category',function ($service){
                return ucfirst($service->category);
            })
            ->addColumn('action', function($service){
                $action = "";
                if(auth()->user()->can('view service'))
                {
                    $action .= '<a href="'.route('spa.overview',['id' => $service->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit service'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-service-btn" id="'.$service->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete service'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-service-btn" id="'.$service->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','name'])
            ->make(true);
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
