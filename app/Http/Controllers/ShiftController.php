<?php

namespace App\Http\Controllers;

use App\Models\EmployeeTable;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Spa;
use Illuminate\Http\Request;
use App\Services\StaffService;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invalidRoles = ['admin', 'super admin', 'owner'];
        $checkRoles = User::whereDoesntHave('roles', function ($query) use ($invalidRoles) {
            $query->whereIn('name', $invalidRoles);
        })->get();
        
        $roles = $checkRoles->map(function ($role) {
            return $role->id;
        });
        
        $employees = EmployeeTable::whereIn('user_id', $roles)->get();
        
        foreach($employees as $employee)
        {
            $shift = Shift::where('employee_id', $employee->id)->get();
            if($shift->isEmpty())
            {
                $currentDateTime = now()->setTimezone('Asia/Manila');
                Shift::create([
                    "user_id" => $employee->user_id,
                    "employee_id" => $employee->id,
                    "Schedule" => 'Mon,Tue,Wed,Thu,Fri',
                    "shift_start" => $currentDateTime->format('h:i A'),
                    "shift_end" => $currentDateTime->format('h:i A'),
                ]);
            }
        }
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
        $checkID = Shift::where('id', $id)->first();

        $timein = Carbon::createFromFormat('H:i', $request->timein, 'Asia/Manila')->format('h:i A');
        $checkID->shift_start = $timein;

        $timeout = Carbon::createFromFormat('H:i', $request->timeout, 'Asia/Manila')->format('h:i A');
        $checkID->shift_end = $timeout;

        $checkID->Schedule = $request->clickedButtons;
        $checkID->Allow_OT = $request->status;
        $checkID->OT = $request->selectOT;
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
        
    }

    public function list(){ 
        
        $shifts = Shift::with('user')->get();
        return DataTables::of($shifts)
        ->editColumn('names',function($shift){
            return $shift->user->firstname;
        })
        ->addColumn('position', function ($shift) {
            $user = User::where('id', $shift->user_id)->first();
            $role = $user->getRoleNames()->first();
            return $role;
        })
        ->editColumn('schedule', function ($shift) {
            $exploded = explode(',', $shift->Schedule);
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
        ->addColumn('time',function ($shift){
            $inandout = $shift->shift_start.'-'. $shift->shift_end;
            return $inandout;
        })
        ->addColumn('action', function($shift){
            $action = "";

            if (auth()->user()->can('edit spa')) {
                $action .= '<button id="edit-shift-'.$shift->id.'" value="'.$shift->id.'" href="#" class="btn btn-sm btn-outline-primary edit-shift-btn my-class" data-target="#schedModal" data-toggle="modal"><i class="fa fa-edit"></i></button>&nbsp;';
            }
            

            return $action;
        })
        ->rawColumns(['action','name', 'schedule'])
        ->make(true);
     }
}
