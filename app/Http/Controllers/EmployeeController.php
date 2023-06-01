<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeTable;
use App\Models\Spa;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

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
    public function create($id)
    {
      
        try {
            $employee = EmployeeTable::findOrFail($id);
            
            $today = now()->format('Y-m-d'); // Get the current date as string

            $attendance = Attendance::where('employee_id', $employee->id)
                    ->whereDate('created_at', $today)
                    ->first();
        
            if ($attendance) {
                return 0;
            }
            else{
                Attendance::create([
                    'employee_id' => $employee->id,
                    'time_in' => $this->phDate(),
                    'time_out' => '-',
                    'break_in' => '-',
                    'break_out' => '-',
                ]);
            }
            return 1;

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return 2;
        }
        
    }

    public function time_out($id)
    {   
        $today = now()->format('Y-m-d');
        try {
            $employee = EmployeeTable::findOrFail($id);
            $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $today)->first();
            if(empty($attendance->time_in)){
                return 3;
            }
            else{
                if ($attendance->time_out == '-') {
                    // Update the time_out column
                    $attendance->update([
                        'time_out' => $this->phDate(),
                    ]);
                    return 0;
                } else  {
                    return 1;
                } 
            }
        
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            return 2;
        }
    }

    public function break_in($id)
    {
        $today = now()->format('Y-m-d');
        try {
            $employee = EmployeeTable::findOrFail($id);
            $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $today)->first();
            if ($attendance->break_in == '-') {
                $attendance->update([
                    'break_in' => $this->phDate(),
                ]);
                return 0;
            } else  {
                return 1;
            } 
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            return 2;
        }
    }

    public function break_out($id)
    {
        $today = now()->format('Y-m-d');
        try {
            $employee = EmployeeTable::findOrFail($id);
            $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $today)->first();
            if(empty($attendance->break_in)){
                return 3;
            }
            else{
                if ($attendance->break_out == '-') {
                    $attendance->update([
                        'break_out' => $this->phDate(),
                    ]);
                    return 0;
                } else  {
                    return 1;
                } 
            }
       
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex){
            return 2;
        }   
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
        $attendance = Attendance::with('employee.user')->whereDate('created_at', $timeStomp)
        ->get()
        ->groupBy('employee_id')
        ->map(function($at) {
            return [
                'name' => $at->first()->employee->user->firstname,
                'time_in' => $at->first()->time_in,
                // 'id' => $at->first()->employee->id,
                'break_in' => $at->first()->break_in,
                'break_out' => $at->first()->break_out,
                'time_out' => $at->first()->time_out,
            ];
        });
        return $attendance->values();
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
    //API
    public function timeInApi($id)
    {
            $exploded = explode('-', $id);
        
            $spaCode = $exploded[0];
            $employeeID = $exploded[1];
        
            $collect = collect();
            $today = now()->format('Y-m-d');
        
            $spas = Spa::where('code', $spaCode)->first();
            $employeetable = EmployeeTable::where('spa_id', $spas->id)->where('id', $employeeID)->get();

            if($employeetable->isEmpty())
            {
                return 2;
            }
            else{

                foreach ($employeetable as $emp) {
                    $collect->push($emp->id);
                }
        
                $mycollect = $collect->first();
                $attendance = Attendance::where('employee_id', $mycollect)->whereDate('created_at', $today)->count();

               if($attendance > 0)
               {
                 return 0;
               }
               else{
                 Attendance::create([
                    'employee_id' => $mycollect,
                    'time_in' => $this->phDate(),
                    'time_out' => '-',
                    'break_in' => '-',
                    'break_out' => '-',
                 ]);
                 return 1;
               }
            }
    }
    public function sample()
    {

        // $spas = Spa::where('code', $code)->first();
        // $employeetable = EmployeeTable::where('spa_id', $spas->id)->get();
        // $collect = collect();
        // foreach ($employeetable as $emp)
        // {
        //     $collect->push($emp->id);
        // }
        // return $collect;
        // $employeetable = EmployeeTable::all();
        // return $employeetable->map(function ($employee) {
        //     return $employee->spas->code;
        // });

    }

    public function timeOutBreakInBreakOutApi($id, $action){
     $today = now()->format('Y-m-d');
    $employee = EmployeeTable::findOrFail($id); //mag where
    $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $today)->first();

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
                        $attendance->$action = $this->phDate();
                        $attendance->update();
                    }
                }
                return 1;
            }
        
    }
}