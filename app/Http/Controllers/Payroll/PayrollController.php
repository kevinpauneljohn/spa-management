<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Spa;
use App\Services\PayrollService;
use App\Services\SpaService;
use App\Services\UserService;
use App\View\Components\Pos\Appointments\UpcomingTab\view;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class PayrollController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:view payroll'])->only(['employeePayroll']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $pageTitle = 'Payroll Management';
        return view('Payroll.updated.index',
            compact('pageTitle'));
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
     * @param string $id
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function show(string $id)
    {
        $spa = Spa::findOrFail($id);
        $pageTitle = $spa->name;
        return view('Payroll.index',compact('spa','pageTitle'));
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

    public function accessPayrollBySpa(PayrollService $payrollService, UserService $userService, SpaService $spaService)
    {
        $ownerId = $userService->get_staff_owner()->id;
        $spas = $spaService->getAllSpaByOwnerId($ownerId);
        return $payrollService->payrollTable($spas);
    }

    public function employeePayroll()
    {
        return view('hr.payroll.payroll');
    }

    public function getEmployeesPayroll(\App\Services\HR\PayrollService $payrollService)
    {
        $owner_id = auth()->user()->owner->id;
        return $payrollService->get_employees_payroll($owner_id);
    }

    public function save_payroll(Request $request, \App\Services\HR\PayrollService $payrollService): \Illuminate\Http\JsonResponse
    {
        $date = explode('-',$request->input('payroll_cut_off'));
        $startDate = Carbon::parse($date[0])->startOfDay();
        $endDate = Carbon::parse($date[1])->endOfDay();
        $owner_id = auth()->user()->owner->id;

        $employees = collect($payrollService->get_employees_id_by_owner_id($owner_id))->toArray();
        if($payrollService->save_payroll($employees, $startDate, $endDate))
        {
            return response()->json(['success' => true, 'message' => 'Payroll Successfully Generated!']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
