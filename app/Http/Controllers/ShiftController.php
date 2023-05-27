<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Shift;
use App\Models\Spa;
use Illuminate\Http\Request;
use App\Services\StaffService;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Attendance.shift');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 
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
        $checkID = Shift::findOrFail($id);
        $checkID->Schedules = $request->list;
        $checkID->time = $request->edit_time;
        $checkID->update();
        return response()->json(['message' => 'Schedule Updated Successfully']);
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

    public function list(){ 
        
        $shift = Shift::with('user')->get();
        
        return DataTables::of($shift)
        ->editColumn('names',function($shifts){
            return $shifts->user->firstname;
        })
        ->addColumn('position', function ($shifts) {
            $user = User::where('id', $shifts->user_id)->first();
            $role = $user->getRoleNames()->first();
            return $role;
        })
        ->editColumn('schedule', function ($shifts) {
            $exploded = explode(',', $shifts->Schedules);
            $exploded = array_map(function ($value) {
                return str_replace(['[', ']', '"'], '', $value);
            }, $exploded);
            $colors = ['primary', 'success', 'warning', 'danger', 'dark','info','secondary']; // Add more colors as needed
            $scheduleLinks = '';
            foreach ($exploded as $index => $schedule) {
                $color = $colors[$index % count($colors)];
                $scheduleLinks .= '<a href="#" class="btn btn-outline-' . $color . '">' . $schedule . '</a> ';
            }
            return $scheduleLinks;
        })
        ->addColumn('time',function ($shifts){
            return $shifts->time;
        })
        ->addColumn('action', function($shifts){
            $action = "";
            // if(auth()->user()->can('view spa'))
            // {
            //     $action .= '<a href="'.route('spa.show',['spa' => $spa->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
            // }
            if(auth()->user()->can('edit spa'))
            {
                $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-shift-btn" data-target="#schedModal" data-toggle="modal" data-id="'.$shifts->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
            }
            // if(auth()->user()->can('delete spa'))
            // {
            //     $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-spa-btn" id="'.$spa->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
            // }
            return $action;
        })
        ->rawColumns(['action','name', 'schedule'])
        ->make(true);
    }
}
