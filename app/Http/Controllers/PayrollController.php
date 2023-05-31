<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeTable;
use App\Models\Spa;
use App\Models\Therapist;
use App\Models\Transaction;
use App\Models\User;
use App\Services\EmployeeService;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class PayrollController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        // if(auth()->user()->hasRole('owner')) {  }
            return view('Payroll.index'); 
    }

    public function therapist(Request $request)
    {
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $data = Therapist::with(['transactions' => function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }])->get()
        ->map(function ($therapist) {
            $amount = $therapist->transactions->sum('amount');
            return (object) [
                'id' => $therapist->id,
                'fullname' => implode(' ', [$therapist->firstname, $therapist->lastname]),
                'amount' => $amount,
                'TotalCommission' => ((($therapist->commission_percentage / 100) * $amount) + $therapist->commission_flat),
            ];
        })->all();

        return $data;
    }

    public function getEmployeeSalary(Request $request){

        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $salary = Attendance::whereBetween('created_at', [$start, $end])->get()
        ->groupBy('employee_id')
        ->map(function($employeeAttendance) {
            return $employeeAttendance->map(function($ftable) {

                $timein = Carbon::parse($ftable->time_in);
                $timeout = Carbon::parse($ftable->time_out);
                return $timeout->diffInHours($timein);

            })->sum();
        });

        $employeeSalary = collect();

        foreach($salary as $employeeId => $totalHours)
        {
            $employee = EmployeeTable::with('user')->find($employeeId); 
            $monthlyRate = $employee->Monthly_Rate;
            $firstName = optional($employee->user)->firstname ?? '';
            $employeeSalary->push([
                'Name' => $firstName,
                'id' => $employee->id,
                'total_hours' => $totalHours,
                'salary' => ($monthlyRate / 192) * $totalHours,
            ]);
        }
        // return $employeeSalary;
        if($employeeSalary->isEmpty()){
            return "No Existing Data";
        }
        else{
            return $employeeSalary;
        }
    }

    public function getSummary(Request $request, $id) {
        
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

    
        $therapist = Therapist::select('firstname', 'lastname', 'id')
        ->where('id', $id)->first();

        $transaction = Transaction::select('service_name', 'amount', 'client_id', 'created_at')
        ->whereDate('transactions.created_at', '>=',$start)
        ->whereDate('transactions.created_at', '<=', $end)
        ->where('therapist_1', $id)->get();


       $collect = array(
        "therapist" => $therapist,
        "transaction" => $transaction
        );

        return $collect;
    }

    public function getEmployeeSummary(Request $request,$id)
    {
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $attendance =  Attendance::where('employee_id', $id)->whereBetween('created_at',[$start,$end])->get();
        
        foreach($attendance as $data){
            $data->Total_Hours = Carbon::parse($data->time_in)->diffInHours(Carbon::parse($data->time_out));
            $data->Pay = (EmployeeTable::where('id', $id)->pluck('Monthly_rate')->first()/192)*$data->Total_Hours;
        }
         return $attendance;
    }

    public function dateLimit()
    {   
        $spa = Spa::pluck('created_at')->min();
        $formattedDate = Carbon::parse($spa)->format('Y-m-d');
        return response()->json(['minDate' => $spa, 'formattedDate' => $formattedDate]);
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
