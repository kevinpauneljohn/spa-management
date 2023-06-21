<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeTable;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\Shift;
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
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use stdClass;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;
use ZipArchive;

use function PHPSTORM_META\map;

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

        $roles = User::whereHas("roles", function($q){
            $q->where("name", ["therapist"]);
        })->get();
        $collect = collect();
        foreach ($roles as $user) {
            $employees = EmployeeTable::with('user')->where('user_id', $user->id)->get();
            
            foreach ($employees as $employee) {
                $attendance = Attendance::where('employee_id', $employee->id)->get()->groupBy('employee_id');
                
                foreach ($attendance as $attendee) {
                    $therapist = Therapist::with(['transactions' => function ($query) use ($start, $end) {
                        $query->whereBetween('created_at', [$start, $end]);
                    }])->where('user_id', $employee->user_id)->get()
                        ->map(function ($thera) use ($attendee,$employee) {
                            $amount = $thera->transactions->sum('amount');
                            $flat = $thera->commission_flat;
                            return (object)[
                              'id' => $thera->id,
                              'fullname' => implode(' ', [$thera->user->firstname, $thera->user->lastname]),
                              'amount' => $amount,
                              'user' => $employee->user,
                              'type' => "Therapist",
                              'TotalCommission' => ((($thera->commission_percentage / 100) * $amount) + ($amount - $flat)),
                              'TotalDays' => $attendee->count(),
                              "EmployeeID" =>  $employee->id,
                              "Allowance" =>  $attendee->count() * $thera->allowance,
                            ];
                        });
                        
                    foreach($therapist as $data){
                         $collect->push($data);
                    }
                }
            }
        }
        
        if($collect->isEmpty()){
            return "No Existing Data";
        }
        else{
            $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
            $payroll = Payroll::where('employee_id', $collect[0]->EmployeeID)->whereDate('created_at', $created)->get();
            $user = $collect[0]->user;
            $role = $user->getRoleNames()->first();

            if ($payroll->isEmpty()) {
                if($role == 'therapist')
                {
                    $payroll = new Payroll();
                    $payroll->employee_id = $collect[0]->EmployeeID;
                    $payroll->name = $collect[0]->fullname;
                    $payroll->type = 'Therapist';
                    $payroll->Allowance = $collect[0]->Allowance;
                    $payroll->amount = $collect[0]->amount;
                    $payroll->TotalCommission = $collect[0]->TotalCommission;
                    $payroll->TotalDays = $collect[0]->TotalDays;
                    $payroll->PayrollRange = now() . ' - ' . now();
                    $payroll->created_at = now()->format('Y-m-d');
                    $payroll->save();
                }
            }
        return $collect;
       }
    }

    public function attendanceCounter(){
        $timein = '2023-06-12 08:00:00';
        $timeout = '2023-06-14 17:00:00';
        $start = Carbon::parse($timein)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($timeout)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $roles = User::whereHas("roles", function($q){
            $q->where("name", ["therapist"]);
        })->get();
        $collect = collect();
        $thera = [];
        foreach ($roles as $user) {
            $employees = EmployeeTable::with('user')->where('user_id', $user->id)->get();
            
            foreach ($employees as $employee) {
                $attendance = Attendance::where('employee_id', $employee->id)->get()->groupBy('employee_id');
                
                foreach ($attendance as $attendee) {
                    $therapist = Therapist::with(['transactions' => function ($query) use ($start, $end) {
                        $query->whereBetween('created_at', [$start, $end]);
                    }])->where('user_id', $employee->user_id)->get()
                        ->map(function ($thera) use ($attendee,$employee) {
                            $amount = $thera->transactions->sum('amount');
                            $flat = $thera->commission_flat;
                            return (object)[
                              'id' => $thera->id,
                              'fullname' => implode(' ', [$thera->user->firstname, $thera->user->lastname]),
                              'amount' => $amount,
                              'type' => "Therapist",
                              'TotalCommission' => ((($thera->commission_percentage / 100) * $amount) + ($amount - $flat)),
                              'TotalDays' => $attendee->count(),
                              "EmployeeID" =>  $employee->id,
                              "Allowance" =>  $attendee->count() * $thera->allowance,
                            ];
                        });
                        
                    foreach($therapist as $data){
                         $collect->push($data);
                    }
                }
            }
        }
        

        if($collect->isEmpty()){
            return "No Existing Data";
        }
        else{
            // $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
            // $payroll = Payroll::where('employee_id', $collect[0]->EmployeeID)->whereDate('created_at', $created)->get();
        
            // if ($payroll->isEmpty()) {
            //     Payroll::create([
            //         'employee_id' => $collect[0]->EmployeeID,
            //         'name' => $collect[0]->fullname,
            //         'type' => 'Therapist',
            //         'Allowance' => $collect[0]->Allowance,
            //         'amount' => $collect[0]->amount,
            //         'TotalCommission' => $collect[0]->TotalCommission,
            //         'TotalDays' => $collect[0]->TotalDays,
            //         'PayrollRange' => now() . ' - ' . now(),
            //     ]);
            // }

          return $collect;
         
        }
            
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
                return $timein->diffInHours($timeout);

            })->sum();
        });

        $employeeSalary = collect();

 
        foreach ($salary as $employeeId => $totalHours) {
            $lateHours = 0;
            $undertimeHours = 0;
            $overtimeHours = 0;
            $employee = EmployeeTable::with('user')->find($employeeId);
            $shifts = Shift::where('employee_id', $employeeId)->get();
            $monthlyRate = $employee->Monthly_Rate;
            foreach ($shifts as $shift) {
                $shiftin = Carbon::parse($shift->shift_start)->format('H:i A');
                $shiftout = Carbon::parse($shift->shift_end)->format('g:i A');

              
                $attendance = Attendance::where('employee_id', $employeeId)->first();

         if ($attendance) {
                $gracePeriod = 15;
                $TotalLate=0;
                $TotalUndertime = 0;
                $TotalOvertime = 0;
                $basicpay = 
                $timein = Carbon::parse($attendance->time_in)->format('H:i A');
                $timeout = Carbon::parse($attendance->time_out)->format('g:i A');;

                $shifthours = Carbon::createFromFormat('H:i A', $shiftin)
                ->diffInHours(Carbon::createFromFormat('H:i A', $shiftout));

                $late = Carbon::createFromFormat('H:i A', $shiftin)
                ->diffInMinutes(Carbon::createFromFormat('H:i A', $timein));

                $UndertimeAndOvertime = Carbon::createFromFormat('H:i A', $shiftout)
                ->diffInMinutes(Carbon::createFromFormat('H:i A', $timeout));
             
                    if($late > $gracePeriod)
                    {
                        $TotalLate = ceil($late/60); 
                        $shifthours = $shifthours - $TotalLate;
                    }
                    if($UndertimeAndOvertime > 0 && $timeout < $shiftout)
                    {
                        $TotalUndertime = $UndertimeAndOvertime/60;
                        $shifthours = $shifthours - $TotalUndertime;
                    }
                    else if ($timeout > $shiftout && $shift->allow_OT == 1 && $UndertimeAndOvertime >= 60) {
                        $TotalOvertime = $UndertimeAndOvertime / 60;
                        $shifthours = $shifthours + floor($TotalOvertime);
                    } 
                }
            }

        $firstName = optional($employee->user)->firstname.' '.optional($employee->user)->lastname ?? '';

        $employeeSalary->push([
            'Name' => $firstName,
            'id' => $employee->id,
            'user' => $employee->user,
            'total_hours' => floor($shifthours),
            'late_Minutes' => $lateHours, 
            'undertime_Minutes' => $undertimeHours,
            'overtime_hours' => $overtimeHours,
            'overtime_Pay' => ($overtimeHours*$monthlyRate)*1.30,
            'Gross_Pay' => ($monthlyRate * ($totalHours-$overtimeHours))+(($overtimeHours*$monthlyRate)*1.30),
            'Net_Pay' => ($monthlyRate * ($totalHours-$overtimeHours))+(($overtimeHours*$monthlyRate)*1.30),
        ]);
    }//end of first foreach

        if($employeeSalary->isEmpty()){
            return "No Existing Data";
        }
        else {
            $employeeSalary->map(function ($empsalary) {
                $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
                $payroll = Payroll::with('employee.user')->where('employee_id', $empsalary['id'])->whereDate('created_at', $created)->first();
        
                if ($payroll === null) {
                    $user = $empsalary['user'];
                    $roles = $user->getRoleNames();
                    $type = $roles->first();
        
                    if ($type !== 'therapist') {
                        $payroll = new Payroll();
                        $payroll->employee_id = $empsalary['id'];
                        $payroll->name = $empsalary['Name'];
                        $payroll->Allowance = 0;
                        $payroll->HollidayPay = 0;
                        $payroll->SSS = 0;
                        $payroll->PAGIBIG = 0;
                        $payroll->PHILHEALTH = 0;
                        $payroll->Loan = 0;
                        $payroll->GrossPay = $empsalary['Gross_Pay'];
                        $payroll->NetPay = $empsalary['Net_Pay'];
                        $payroll->PayrollRange = now() . ' - ' . now();
                        $payroll->created_at = now()->format('Y-m-d');
                        $payroll->save();
        
                        $payroll->type = 'Employee';
                        $payroll->save();
                    }
                }
            });
            $filteredEmployees = collect($employeeSalary)->filter(function ($employee) {
                return $employee['user']->roles->where('name', 'therapist')->isEmpty();
            });
            return response()->json($filteredEmployees);
        }
        
        
        
    }


    public function practice()
    {
        

        $timein = '2023-06-01 08:00:00';
        $timeout = '2023-06-30 17:00:00';
      
        $start = Carbon::parse($timein)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($timeout)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $salary = Attendance::whereBetween('created_at', [$start,$end])->get()
        ->groupBy('employee_id')
        ->map(function($employeeAttendance) {
            return $employeeAttendance->map(function($ftable) {

                $timein = Carbon::parse($ftable->time_in);
                $timeout = Carbon::parse($ftable->time_out);
                
                return $timein->diffInHours($timeout);

            })->sum();
        });
        $employeeSalary = collect();

        foreach ($salary as $employeeId => $totalHours) {
            $lateHours = 0;
            $undertimeHours = 0;
            $overtimeHours = 0;
            $employee = EmployeeTable::with('user')->find($employeeId);
            $shifts = Shift::where('employee_id', $employeeId)->get();
            $monthlyRate = $employee->Monthly_Rate;
            foreach ($shifts as $shift) {
                $shiftin = Carbon::parse($shift->shift_start)->format('H:i A');
                $shiftout = Carbon::parse($shift->shift_end)->format('g:i A');

              
                $attendance = Attendance::where('employee_id', $employeeId)->first();

         if ($attendance) {
                $gracePeriod = 15;
                $TotalLate=0;
                $TotalUndertime = 0;
                $TotalOvertime = 0;
                $basicpay = 
                $timein = Carbon::parse($attendance->time_in)->format('H:i A');
                $timeout = Carbon::parse($attendance->time_out)->format('g:i A');;

                $shifthours = Carbon::createFromFormat('H:i A', $shiftin)
                ->diffInHours(Carbon::createFromFormat('H:i A', $shiftout));

                $late = Carbon::createFromFormat('H:i A', $shiftin)
                ->diffInMinutes(Carbon::createFromFormat('H:i A', $timein));

                $UndertimeAndOvertime = Carbon::createFromFormat('H:i A', $shiftout)
                ->diffInMinutes(Carbon::createFromFormat('H:i A', $timeout));
             
                    if($late > $gracePeriod)
                    {
                        $TotalLate = ceil($late/60); 
                        $shifthours = $shifthours - $TotalLate;
                    }
                    if($UndertimeAndOvertime > 0 && $timeout < $shiftout)
                    {
                        $TotalUndertime = $UndertimeAndOvertime/60;
                        $shifthours = $shifthours - $TotalUndertime;
                    }
                    else if ($timeout > $shiftout && $shift->allow_OT == 1 && $UndertimeAndOvertime >= 60) {
                        $TotalOvertime = $UndertimeAndOvertime / 60;
                        $shifthours = $shifthours + floor($TotalOvertime);
                    } 
                }
            }

        $firstName = optional($employee->user)->firstname ?? '';

        $employeeSalary->push([
            'Name' => $firstName,
            'id' => $employee->id,
            'total_hours' => floor($shifthours),
            'late_Minutes' => $lateHours, 
            'undertime_Minutes' => $undertimeHours,
            'overtime_hours' => $overtimeHours,
            'overtime_Pay' => ($overtimeHours*$monthlyRate)*1.30,
            'Gross_Pay' => ($monthlyRate * ($totalHours-$overtimeHours))+(($overtimeHours*$monthlyRate)*1.30),
            'Net_Pay' => ($monthlyRate * ($totalHours-$overtimeHours))+(($overtimeHours*$monthlyRate)*1.30),
        ]);
    }//end of first foreach

        if($employeeSalary->isEmpty()){
            return "No Existing Data";
        }
        else{
            $employeeSalary->map(function($empsalary) {
                $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
                // $payroll = Payroll::where('employee_id', $empsalary['id'])->whereDate('created_at', $created)->get();
                $payroll = Payroll::with('employee.user')->where('employee_id', $empsalary['id'])->whereDate('created_at', $created)->get();
                if ($payroll->isEmpty()) {
                    $payroll = new Payroll();
                    $payroll->employee_id = $empsalary['id'];
                    $payroll->name = $empsalary['Name'];
                    $payroll->type = 'Employee';
                    $payroll->Allowance = 0;
                    $payroll->HollidayPay = 0;
                    $payroll->SSS = 0;
                    $payroll->PAGIBIG = 0;
                    $payroll->PHILHEALTH = 0;
                    $payroll->Loan = 0;
                    $payroll->GrossPay = $empsalary['Gross_Pay'];
                    $payroll->NetPay = $empsalary['Net_Pay'];
                    $payroll->PayrollRange = now() . ' - ' . now();
                    
                    $payroll->save();
                    
                }
            });
            
            return response()->json($employeeSalary);
        }

    }
  

    public function getSummary(Request $request, $id) {
        
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $collection = collect();
        $therapists = Therapist::where('id', $id)->get();
        foreach($therapists as $therapist)
        {
           $transactions =  Transaction::where('therapist_1', $therapist->id)
           ->whereDate('transactions.created_at', '>=',$start)
           ->whereDate('transactions.created_at', '<=', $end)->get();
           $users = User::where('id', $therapist->user_id)->get();
            foreach($users as $user)
            {
                foreach($transactions as $transaction)
                {
                  $collection->push([
                    "fullname" => $user->firstname.' '. $user->lastname,
                    "service" => $transaction->service_name,
                    "amount" => $transaction->amount,
                    "date" => $transaction->created_at,
                  ]);
                }
            }
        }
        return $collection;

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
    public function payslip(Request $request,$type)
    {
        // $timein = '2023-06-01 08:00:00';
        // $timeout = '2023-06-30 17:00:00';
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d');

        
        $payrolls = Payroll::with('employee')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('type', $type)->get();
   
        if ($payrolls->isEmpty()) {
            return 404;
        }
        // return $payrolls;
        $zip = new ZipArchive();
        $zipFileName = 'payslips.zip';

        $tempDir = sys_get_temp_dir() . '/' . uniqid();

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        foreach ($payrolls as $payroll) {
            $name = $payroll->employee->user->firstname;
            $id = $payroll->employee->id;
            $spaName = $payroll->employee->spas->name;
            $role = $payroll->employee->user->getRoleNames()->first();
            $allowance = $payroll->Allowance;
            $pdf = new Dompdf();

        if ($type === 'Employee') {
      
                $basicpay = $payroll->{'Basic Pay'};
                $SSS = $payroll->SSS;
                $PAGIBIG = $payroll->PAGIBIG;
                $PHILHEALTH = $payroll->PHILHEALTH;
                $loan = $payroll->Loan;
                $grosspay = $payroll->GrossPay;
                $netpay = $payroll->NetPay;
                $amount = $payroll->amount;
                $totalcom = $payroll->TotalCommission;
                $totaldays = $payroll->TotalDays;
                 $pdf->loadHtml(View::make('Payroll.payslip', compact('name', 'id', 'spaName', 'role', 'basicpay', 'allowance', 'SSS', 'PAGIBIG', 'PHILHEALTH', 'loan', 'grosspay', 'netpay', 'amount', 'totalcom', 'totaldays'))->render());

        } 
        else if ($type === 'Therapist') {
      
            $amount = $payroll->amount;
            $totalcom = $payroll->TotalCommission;
            $totaldays = $payroll->TotalDays;
            $pdf->loadHtml(View::make('Payroll.therapistpayslip', compact('name', 'id', 'allowance', 'spaName', 'role', 'amount', 'totalcom', 'totaldays'))->render());

        }

        $pdf->render();

            $filename = $tempDir . '/payslip_' . $payroll->id . '.pdf';
            file_put_contents($filename, $pdf->output());
        }

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = glob($tempDir . '/*.pdf');

            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            foreach ($files as $file) {
                unlink($file);
            }
            rmdir($tempDir);

            // Download the zip file
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        }

    }
//viewer
    public function therapistPayslip()
    {   
        return view('Payroll.therapistpayslip');
    }

    public function payslipview()
    {   
       return view('Payroll.payslip');
    }
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
