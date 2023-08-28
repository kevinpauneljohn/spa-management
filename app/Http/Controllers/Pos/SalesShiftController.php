<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesShiftRequest;
use App\Services\PointOfSales\Shift\ShiftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalesShiftController extends Controller
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
     * @param SalesShiftRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SalesShiftRequest $request, ShiftService $shiftService)
    {
        return $shiftService->start($request->start_money)
            ? response()->json(['success' => true, 'message' => 'Shift started!'])
            : response()->json(['success' => false, 'message' => 'an error occurred']);
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

    /**
     * @param $spaId
     * @param ShiftService $shiftService
     * @return JsonResponse
     */
    public function endShift($spaId, ShiftService $shiftService): JsonResponse
    {
        return $shiftService->end($spaId) ?
            \response()->json(['success' => true, 'message' => 'Shift Ended'])
            : \response()->json(['success' => false, 'message' => 'An error occurred!']);
    }
}
