<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Spa;
use App\Services\PayrollService;
use App\Services\SpaService;
use App\Services\UserService;
use App\View\Components\Pos\Appointments\UpcomingTab\view;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class PayrollController extends Controller
{

    public function __construct()
    {

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
}
