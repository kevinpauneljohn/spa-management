<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeTable;
use App\Models\Spa;
use App\Models\Therapist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            // ->toDateString()
            
            // Check if attendance record already exists for this employee and date
            $attendance = Attendance::where('employee_id', $employee->id)
                    ->whereDate('created_at', $today)
                    ->first();
        
            if ($attendance) {
                return "Attendance record already exists for this employee for today.";
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
        
            // Create new attendance record for this employee
            return "Successfully Created!";

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return 'ID '.$id. ' Not Found!';
        }
        
    }

    public function time_out($id){
        
        $Attendancetable = Attendance::select('time_out')->where('employee_id', $id)->first();

          Attendance::where('employee_id', $id)->update(['time_out' => $this->phDate()]);
          if(empty($Attendancetable)){
            return "Employee does not exist!";
          } else{return "Time-out successful!";}     
    }
    public function break_in($id){
        
        $Attendancetable = Attendance::select('time_out')->where('employee_id', $id)->first();

          Attendance::where('employee_id', $id)->update(['break_in' => $this->phDate()]);
          if(empty($Attendancetable)){
            return "Employee does not exist!";
          } else{return "Break-in successful!";}     
    }
    public function break_out($id){
        
        $Attendancetable = Attendance::select('time_out')->where('employee_id', $id)->first();

          Attendance::where('employee_id', $id)->update(['break_out' => $this->phDate()]);
          if(empty($Attendancetable)){
            return "Employee does not exist!";
          } else{return "Break-out successful!";}     
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
        $displayAttendance =  Attendance::whereDate('created_at', $timeStomp)->get()->toArray();
        return response()->json($displayAttendance);
        
    }
    public function getCode(){
       
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
}
