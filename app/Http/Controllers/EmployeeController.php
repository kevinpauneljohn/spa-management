<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeTable;
use App\Models\Owner;
use App\Models\Shift;
use App\Models\Spa;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Attendance.index');
    }   
    
    public function phDate()
    {
        return Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {   
        $timeStomp = now()->format('Y-m-d');
     
        $attendances = Attendance::with('employee.user')->whereDate('created_at', $timeStomp)
        ->get()
        ->groupBy('employee_id');
         return DataTables::of($attendances)
         ->editColumn('name', function($attendance){
            return $attendance->first()->employee->user->firstname;
         })
         ->addColumn('timein', function($attendance){
            return $attendance->first()->time_in;
         })
         ->addColumn('breakin', function($attendance){
            return $attendance->first()->break_in;
         })
         ->addColumn('breakout', function($attendance){
            return $attendance->first()->break_out;
         })
         ->addColumn('timeout', function($attendance){
            return $attendance->first()->time_out;
         })
         ->rawColumns(['name'])
         ->make(true);
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
        //
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
    public function setting()
    {
        $users = User::whereHas("roles", function($q){ $q->whereNotIn("name", ["super admin","owner"]); })->get();
        $collect = collect();
        foreach($users as $user)
        {
           $collect->push($user->id);
        }
        $employees =  EmployeeTable::where('user_id', $collect)->get();
        // $role = $collect->getRoleNames()->first();
        return $employees;
    }
    //API

    public function timeInApi($id, $spaCode)
    {
     
            $collect = collect();
            $today = now()->format('Y-m-d');
        
            $spas = Spa::where('code', $spaCode)->first();
            if(empty($spas))
            {
                return 3;
            }
            else{    
                $employeetable = EmployeeTable::where('spa_id', $spas->id)->where('id', $id)->get();
                if($employeetable->isEmpty())
                {
                    return 2;
                }
                else 
                {
                    foreach ($employeetable as $emp) {
                        $collect->push($emp->id);
                    }
            
                    $mycollect = $collect->first();
                    $attendance = Attendance::where('employee_id', $mycollect)->whereDate('created_at', $today)->where('time_in', '!=', '-')->count();

                    if($attendance > 0)
                    {
                        return 0;
                    }
                    else{
                        $shifts = Shift::where('employee_id', $mycollect)->pluck('Schedule')->map(function ($item) {
                            return json_decode($item);
                        })->flatten()->toArray();
                        $currentDay = Carbon::now()->format('D');
                        if (in_array($currentDay, $shifts)) {
                            Attendance::create([
                                'employee_id' => $mycollect,
                                'time_in' => $this->phDate(),
                                'time_out' => '-',
                                'break_in' => '-',
                                'break_out' => '-',
                                'allow_OT' => 0,
                                'OT' => 0,
                            ]);
                            return 1;
                        } else {
                            return 4;
                        }
                    }
                }
            }
        }
        
    

    public function timeOutBreakInBreakOutApi($id,$action){
     $today = now()->format('Y-m-d');
     $exploded = explode('-', $id);
     $collect = collect();
     $spaCode = $exploded[0];
     $employeeID = $exploded[1];
    
     $spa = Spa::where('code', $spaCode)->first();
        if(empty($spa))
        {
            return 4;
        }
        else
        {
            $employee = EmployeeTable::where('spa_id', $spa->id)->where('id', $employeeID)->get();
            if($employee->isEmpty()){
                return 3;
               }
               else
               {
                    foreach($employee as $emp)
                    {
                        $collect->push($emp->id);
                    }
                    $IDChecker = $collect->first();
                    $attendance = Attendance::where('employee_id', $IDChecker)->whereDate('created_at', $today)->first();
                    
                    if(empty($attendance->time_in))
                    {
                        return 0;
                    }
                    else{
                        if($action=='break_in'){
                            if($attendance->time_in != '-' && $attendance->time_out == '-' && $attendance->break_in == '-'){
                                $attendance->$action = $this->phDate();
                                $attendance->update();
                                return 1;
                            }
                            else{
                                return 0;
                            }
                        }
                        elseif($action=='break_out'){
                            if($attendance->time_in != '-' && $attendance->time_out == '-' && $attendance->break_in != '-'){
                                $attendance->$action = $this->phDate();
                                $attendance->update();
                                return 1;
                            }
                            else{
                                return 0 ;
                            }
                        }
                        else{
                            
                            if($attendance->time_out != '-'){
                                return 2;
                            }
                            else{
                                $user = Shift::where('employee_id', $employeeID)->first();
                         
                                $attendance->$action = $this->phDate();
                                $timeout = Carbon::parse($attendance->time_out)->format('H:i A');
                                $shiftout = Carbon::parse($user->shift_end)->format('g:i A');
                                $ot = Carbon::createFromFormat('H:i A', $timeout)->diffInMinutes(Carbon::createFromFormat('H:i A', $shiftout));
                                if($timeout >= $shiftout && $user->allow_OT == 1){
                                    $attendance->allow_OT = $user->allow_OT;
                                    $attendance->OT = $user->OT;
                                    
                                    $attendance->update();
                                }
                                    $user->allow_OT = 0;
                                    $user->OT = 0;
                                    $attendance->update(); 
                                    $user->update();                             
                            }
                        }
                        
                        return 1;
                    }
               }
        }
    }
    
}