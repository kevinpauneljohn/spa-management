<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\AdditionalPay;
use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\HR\DeductionService;
use App\Services\HR\PayrollService;
use App\Services\HR\PayslipService;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, DeductionService $deductionService)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
        ]);

        if($deductionService->add_new_deduction(collect($request->all())->toArray()))
        {
            return response()->json(['success' => true, 'message' => 'Payslip added successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred!.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id, PayrollService $payrollService)
    {
        $payroll = Payroll::findOrFail($id);
        $employee = Employee::findOrFail($payroll->employee_id);
        $attendance = Attendance::where('userid',$employee->biometric->userid)->whereBetween('time_in',[$payroll->date_start, $payroll->date_end]);
        $number_of_days_worked = $payrollService->get_number_of_days_worked($employee->biometric->userid,$payroll->date_start, $payroll->date_end);
        $gross_basic_pay = $payrollService->daily_basic_pay_times_number_of_worked_days($employee->biometric->userid,$payroll->date_start, $payroll->date_end);
        $deductions = Deduction::where('payroll_id',$payroll->id);
        $additionalPay = AdditionalPay::where('payroll_id', $payroll->id)->get();
        $net_pay = ( $gross_basic_pay + $attendance->sum('overtime_pay') + $additionalPay->sum('amount')) - ( $attendance->sum('total_late_hours') + $deductions->sum('amount'));
        return view('hr.payslip.payslip',compact('payroll','employee','attendance','number_of_days_worked','gross_basic_pay','deductions', 'additionalPay', 'net_pay'));
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
