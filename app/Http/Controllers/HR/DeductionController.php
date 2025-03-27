<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Services\HR\DeductionService;
use App\Services\HR\PayslipService;
use Illuminate\Http\Request;

class DeductionController extends Controller
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Deduction::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $deduction = Deduction::find($id)->fill($request->all());
        if($deduction->isDirty())
        {
            $deduction->save();
            return response()->json(['success' => true, 'message' => 'Deduction updated!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return Deduction::findOrFail($id)->delete() ?
            response()->json(['success' => true, 'message' => 'Deduction successfully removed!']) :
            response()->json(['success' => false, 'message' => 'An error occurred!']) ;
    }

    public function get_deductions($owner_id, $payroll_id, DeductionService $deductionService, PayslipService $payslipService)
    {
        return $deductionService->get_deductions_datatable($owner_id, $payroll_id);
    }
}
