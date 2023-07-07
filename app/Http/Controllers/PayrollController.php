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
use Exception;
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
    public $collect;

    public function __construct()
    {
            $this->collect = collect();
    }
    public function index()
    { 
        // if(auth()->user()->hasRole('owner')) {  }
            return view('Payroll.payrollindex'); 
    }

    public function therapist(Request $request)
    {
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $users = User::whereHas("roles", function($q){
            $q->where("name", ["therapist"]);
        })->get();
        $collect = collect();
        foreach($users as $user)
        {
             EmployeeTable::with(['attendances','user.therapist.transactions' => function($query) use ($start,$end){
                $query->whereBetween('created_at', [$start, $end]);
            }])->where('user_id', $user->id)->get()
            ->map(function($emp) use ($collect){   
               $amount = $emp->user->therapist->transactions->sum('amount'); 
               $flat = $emp->user->therapist->commission_flat;
                $collect->push((object)[
                    'id' => $emp->user->therapist->id,
                    'fullname' => implode(' ', [$emp->user->firstname, $emp->user->lastname]),
                    'amount' => $amount,
                    // 'user' => $emp->user,
                    'type' => "Therapist",
                    'TotalCommission' => ((($emp->user->therapist->commission_percentage / 100) * $amount) + ($emp->user->therapist->transactions->count() * $flat)),
                    'TotalDays' => $emp->attendances->count(),
                    "EmployeeID" =>  $emp->id,
                    "Allowance" =>  $emp->attendances->count() * (int)$emp->user->therapist->allowance,
                ]);
            });
        }

        if($collect->isEmpty()){
            return "No Existing Data";
        }
        else{
            $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
            foreach($collect as $items)
            {
                 $payroll = Payroll::where('employee_id', $items->EmployeeID)->whereDate('created_at', $created)->get();
                 if ($payroll->isEmpty()) {
                    $payroll = new Payroll();
                    $payroll->employee_id = $items->EmployeeID;
                    $payroll->name = $items->fullname;
                    $payroll->type = 'Therapist';
                    $payroll->allowance = $items->Allowance;
                    $payroll->amount = $items->amount;
                    $payroll->TotalCommission = $items->TotalCommission;
                    $payroll->TotalDays = $items->TotalDays;
                    $payroll->PayrollRange = now() . ' - ' . now();
                    $payroll->save();
                }
            }
            return $collect;
        }
    }
    public function practice()
    {
        $date1 = '2023-07-20 07:09:20';
        $date2 = '2023-07-30 07:09:20';
        $start = Carbon::parse($date1)->setTimezone('Asia/Manila')->format('Y-m-d');
        $end = Carbon::parse($date2)->setTimezone('Asia/Manila')->format('Y-m-d');

        $late = 0;
        $underTime = 0;
        $OT = 0;
        $break = 1;
        $dailyRate = 0;
        $deduction = 0;
      
        //$collect = collect();
        
        $invalidRoles = ['therapist','admin','super admin','owner'];
        $checkRoles = User::whereDoesntHave('roles', function ($query) use ($invalidRoles) {
            $query->whereIn('name', $invalidRoles);
        })->get();

        foreach($checkRoles as $user){
            $employees = EmployeeTable::where('user_id', $user->id)->with(['shift','user','attendances' => function($attendance) use($start,$end){
                $attendance->whereBetween('time_in',[$start, $end]);
            }])->get()->map(function($employee) use ($late, $underTime, $OT, $deduction, $dailyRate){ 

                $shiftin = Carbon::parse($employee->shift->first()->shift_start)->format('H:i A');
                $shiftout = Carbon::parse($employee->shift->first()->shift_end)->format('g:i A');   
                $diffShift = Carbon::createFromFormat('H:i A', $shiftin)->diffInHours(Carbon::createFromFormat('H:i A', $shiftout));
        
                foreach($employee->attendances as $timeLoss) {
                $timeIn = Carbon::parse($timeLoss->time_in)->format('H:i A');
                $timeOut = Carbon::parse($timeLoss->time_out)->format('g:i A');
                $diffTime = Carbon::createFromFormat('H:i A', $timeIn)->diffInMinutes(Carbon::createFromFormat('H:i A', $shiftin));

                
                $OT = $OT + $timeLoss->OT;
                $dailyRate = $employee->Daily_Rate;
                    if($timeIn > $shiftin && $diffTime >= 15 )
                    {
                        $late = $late + $diffTime;
                        $deduction = $deduction + 100;
                        $dailyRate = $dailyRate - $deduction;
                        if($timeIn > $shiftin && $diffTime >= 30 && $dailyRate > 0){
                            $deduction = $deduction + 100;
                            $dailyRate = $dailyRate - $deduction;
                            if($timeIn > $shiftin && $diffTime >= 45 && $dailyRate > 0){
                                $deduction = $deduction + 100;
                                $dailyRate = $dailyRate - $deduction;                
                                if($timeIn > $shiftin && $diffTime >= 60 && $dailyRate > 0){
                                    $deduction = $deduction + 100;
                                    $dailyRate = $dailyRate - $deduction;                      
                                }     
                            }
                        }
                    }
                    
                    if($timeOut < $shiftout)
                    {
                        $holder = Carbon::createFromFormat('H:i A', $timeIn)->diffInMinutes(Carbon::createFromFormat('H:i A', $timeOut));
                        $underTime =  (floor($holder/60) + $underTime) - $diffShift;
                    }      
                 }
                 $this->collect->push((object)[
                    "Name" => $employee->user->firstname.' '.$employee->user->lastname,
                    "id" => $employee->id,
                    "shift_hours" => $diffShift,
                    "Total_attendance" => $employee->attendances->count(),
                    "Quota" => $diffShift * $employee->attendances->count(),
                    "overtime_hours" => $OT,
                    "overtime_Pay" => (($employee->Daily_Rate/$diffShift)*$OT)*1.3,
                    "Total_working_hours" => (floor($diffShift) * $employee->attendances->count()) - (($late/60) + abs($underTime) + $employee->attendances->count()),
                    "Gross_Pay" => round(($employee->attendances->count() * $dailyRate) + (($employee->Daily_Rate/$diffShift)*1.3),2),
                    "Late" => $late,
                    "Total_break_Hours" => $employee->attendances->count(),
                    "Under_time" => abs($underTime),
                    "Net_Pay" => round((($employee->attendances->count() * $dailyRate) - $deduction) + ((($employee->Daily_Rate/$diffShift)*$OT)*1.3)),
                 ]);
            });     
        }
   
            foreach($this->collect as $data)
            {
                if($data->Net_Pay ==0)
                {
                    return "No Existing Data";
                }
                else
                {
                    $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
                    $payroll = Payroll::with('employee.user')->where('employee_id', $data->id)->whereDate('created_at', $created)->first();
                    if ($payroll === null) {
    
                            $payroll = new Payroll();
                            $payroll->employee_id = $data->id;
                            $payroll->name = $data->Name;
                            $payroll->{'Basic Pay'} = 0;
                            $payroll->GrossPay = $data->Gross_Pay;
                            $payroll->TotalDays = $data->Total_attendance;
                            $payroll->NetPay = $data->Net_Pay;
                            $payroll->PayrollRange = now() . ' - ' . now();
                            $payroll->created_at = now()->format('Y-m-d');
                            $payroll->hour_render = $data->Total_working_hours;
                            $payroll->late_hours = $data->Late;
                            $payroll->undertime = $data->Under_time;

                            $payroll->type = 'Employee';
                            $payroll->save();
                    }

                }
                return $this->collect;
            }
    }
    
    public function getEmployeeSalary(Request $request){

        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d');

        
        $late = 0;
        $underTime = 0;
        $OT = 0;
        $break = 1;
        $dailyRate = 0;
        $deduction = 0;
      
        //$collect = collect();
        
        $invalidRoles = ['therapist','admin','super admin','owner'];
        $checkRoles = User::whereDoesntHave('roles', function ($query) use ($invalidRoles) {
            $query->whereIn('name', $invalidRoles);
        })->get();

        foreach($checkRoles as $user){
            $employees = EmployeeTable::where('user_id', $user->id)->with(['shift','user','attendances' => function($attendance) use($start,$end){
                $attendance->whereBetween('time_in',[$start, $end]);
            }])->get()->map(function($employee) use ($late, $underTime, $OT, $deduction, $dailyRate){ 

                $shiftin = Carbon::parse($employee->shift->first()->shift_start)->format('H:i A');
                $shiftout = Carbon::parse($employee->shift->first()->shift_end)->format('g:i A');   
                $diffShift = Carbon::createFromFormat('H:i A', $shiftin)->diffInHours(Carbon::createFromFormat('H:i A', $shiftout));
        
                foreach($employee->attendances as $timeLoss) {
                $timeIn = Carbon::parse($timeLoss->time_in)->format('H:i A');
                $timeOut = Carbon::parse($timeLoss->time_out)->format('g:i A');
                $diffTime = Carbon::createFromFormat('H:i A', $timeIn)->diffInMinutes(Carbon::createFromFormat('H:i A', $shiftin));

                
                $OT = $OT + $timeLoss->OT;
                $dailyRate = $employee->Daily_Rate;
                    if($timeIn > $shiftin && $diffTime >= 15 )
                    {
                        $late = $late + $diffTime;
                        $deduction = $deduction + 100;
                        $dailyRate = $dailyRate - $deduction;
                        if($timeIn > $shiftin && $diffTime >= 30 && $dailyRate > 0){
                            $deduction = $deduction + 100;
                            $dailyRate = $dailyRate - $deduction;
                            if($timeIn > $shiftin && $diffTime >= 45 && $dailyRate > 0){
                                $deduction = $deduction + 100;
                                $dailyRate = $dailyRate - $deduction;                
                                if($timeIn > $shiftin && $diffTime >= 60 && $dailyRate > 0){
                                    $deduction = $deduction + 100;
                                    $dailyRate = $dailyRate - $deduction;                      
                                }     
                            }
                        }
                    }
                    
                    if($timeOut < $shiftout)
                    {
                        $holder = Carbon::createFromFormat('H:i A', $timeIn)->diffInMinutes(Carbon::createFromFormat('H:i A', $timeOut));
                        $underTime =  (floor($holder/60) + $underTime) - $diffShift;
                    }      
                 }
                 $this->collect->push((object)[
                    "Name" => $employee->user->firstname.' '.$employee->user->lastname,
                    "id" => $employee->id,
                    "shift_hours" => $diffShift,
                    "Total_attendance" => $employee->attendances->count(),
                    "Quota" => $diffShift * $employee->attendances->count(),
                    "overtime_hours" => $OT,
                    "overtime_Pay" => (($employee->Daily_Rate/$diffShift)*$OT)*1.3,
                    "Total_working_hours" => (floor($diffShift) * $employee->attendances->count()) - (($late/60) + abs($underTime) + $employee->attendances->count()),
                    "Gross_Pay" => round(($employee->attendances->count() * $dailyRate) + (($employee->Daily_Rate/$diffShift)*1.3),2),
                    "Late" => $late,
                    "Total_break_Hours" => $employee->attendances->count(),
                    "Under_time" => abs($underTime),
                    "Net_Pay" => round((($employee->attendances->count() * $dailyRate) - $deduction) + ((($employee->Daily_Rate/$diffShift)*$OT)*1.3)),
                 ]);
            });     
        }
        $hasdata = false;
            foreach($this->collect as $data)
            {
                if($data->Net_Pay ==0)
                {
                   $hasdata = false;
                }
                else
                {
                    $hasdata = true;
                    $created = Carbon::parse(now())->setTimezone('Asia/Manila')->format('Y-m-d');
                    $payroll = Payroll::with('employee.user')->where('employee_id', $data->id)->whereDate('created_at', $created)->first();
                    if ($payroll === null) {
    
                            $payroll = new Payroll();
                            $payroll->employee_id = $data->id;
                            $payroll->name = $data->Name;
                            $payroll->{'Basic Pay'} = 0;
                            $payroll->GrossPay = $data->Gross_Pay;
                            $payroll->TotalDays = $data->Total_attendance;
                            $payroll->NetPay = $data->Net_Pay;
                            $payroll->PayrollRange = now() . ' - ' . now();
                            $payroll->created_at = now()->format('Y-m-d');
                            $payroll->hour_render = $data->Total_working_hours;
                            $payroll->late_hours = $data->Late;
                            $payroll->undertime = $data->Under_time;

                            $payroll->type = 'Employee';
                            $payroll->save();
                    }

                }
                
            }
            if($hasdata == false)
            {
                return 404;
            }
            else{
                return $this->collect;
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

    public function getEmployeeSummary(Request $request, $id)
    {
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d');
        // $date1 = '2023-07-01 07:09:20';
        // $date2 = '2023-07-30 07:09:20';
        // $start = Carbon::parse($date1)->setTimezone('Asia/Manila')->format('Y-m-d');
        // $end = Carbon::parse($date2)->setTimezone('Asia/Manila')->format('Y-m-d');
        $attendances =  Attendance::with(['employee.shift'])->where('employee_id', $id)->whereBetween('created_at',[$start,$end])->get();
        
        foreach($attendances as $attendance)
        {

           $TotalHours = Carbon::parse($attendance->time_in)->diffInHours(Carbon::parse($attendance->time_out));
           $TotalMinutes = Carbon::parse($attendance->time_in)->diffInMinutes(Carbon::parse($attendance->time_out));
           $decimal = fmod(($TotalMinutes-60)/60, 1);
           

           $timeIn = Carbon::parse($attendance->time_in)->format('H:i A');
           $shiftIn = Carbon::parse($attendance->employee->shift[0]->shift_start)->format('H:i A');
            $diffShift = Carbon::createFromFormat('H:i A', $timeIn)->diffInMinutes(Carbon::createFromFormat('H:i A', $shiftIn));


       
            $dailyRate = $attendance->employee->Daily_Rate;
            $deduction = 0;
            $late =0;
     
            if($timeIn > $shiftIn && $diffShift >= 15 )
            {
                $late = $late + $diffShift;
                $deduction = $deduction + 100;
                $dailyRate = $dailyRate - $deduction;
                if($timeIn > $shiftIn && $diffShift >= 30 && $dailyRate > 0){
                    $deduction = $deduction + 100;
                    $dailyRate = $dailyRate - $deduction;
                    if($timeIn > $shiftIn && $diffShift >= 45 && $dailyRate > 0){
                        $deduction = $deduction + 100;
                        $dailyRate = $dailyRate - $deduction;                
                        if($timeIn > $shiftIn && $diffShift >= 60 && $dailyRate > 0){
                            $deduction = $deduction + 100;
                            $dailyRate = $dailyRate - $deduction;                      
                        }     
                    }
                }
            }
            if($dailyRate < 0)
             {
                $dailyRate =0;
            }
           $this->collect->push([
                "Date" => $attendance->time_in,
                "Total_Hours" =>  $TotalHours - 1 .':'. $decimal*60,
                "Pay" => $dailyRate
            ]);
        }
        return $this->collect;

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
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d');

        
        $payrolls = Payroll::with('employee')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('type', $type)->get();
        $data = [];

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
                $totaldays = $payroll->TotalDays;
                $hour_render = $payroll->hour_render;
                $late_hours = $payroll->late_hours;
                $undertime = $payroll->undertime;
                $pdf->loadHtml(View::make('Payroll.payslips', compact('name', 'id', 'spaName', 'role', 'basicpay', 'allowance', 'SSS', 'PAGIBIG', 'PHILHEALTH', 'loan', 'grosspay', 'netpay', 'totaldays','hour_render','late_hours','undertime'))->render());

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
