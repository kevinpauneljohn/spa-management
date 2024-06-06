<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesShiftRequest;
use App\Models\SalesShift;
use App\Services\PointOfSales\Shift\ShiftService;
use App\Services\UserService;
use App\View\Components\Pos\Appointments\UpcomingTab\view;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalesShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:owner'])->only(['lists']);
    }

    public function index()
    {
        return view('SalesShift.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return SalesShift::find($id)->delete() ?
        \response()->json(['success' => true, 'message' => 'Shift deleted'])
            : \response()->json(['success' => false, 'message' => 'An error occurred!']);
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

    public function endShiftByOwner($id, ShiftService $shiftService)
    {
        return $shiftService->endShiftByOwner($id) ?
            \response()->json(['success' => true, 'message' => 'Shift Ended'])
            : \response()->json(['success' => false, 'message' => 'An error occurred!']);
    }

    public function lists(ShiftService $shiftService, UserService $userService)
    {
        $spaIds = collect($userService->get_staff_owner()->spas)->pluck('id')->toArray();
        return $shiftService->salesShiftLists(SalesShift::whereIn('spa_id',$spaIds)->get());
    }
}
