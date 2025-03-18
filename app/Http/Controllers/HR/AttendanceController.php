<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\HR\AttendanceService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(UserService $userService)
    {
        return view('hr.attendances.index')
            ->with('owner_id', $userService->get_staff_owner()->id);
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
     * @return array
     */
    public function store(Request $request, AttendanceService $attendanceService)
    {
        return $attendanceService->saveAttendance($request->state,$request->timestamp,$request->id);
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
        return Attendance::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAttendanceRequest $request, $id, AttendanceService $attendanceService)
    {
        return response()->json($attendanceService->updateAttendance(
            $id,
            [
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'break_in' => $request->break_in,
                'break_out' => $request->break_out
            ]
        ));
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

    public function storeAttendance(Request $request, AttendanceService $attendanceService): \Illuminate\Http\JsonResponse
    {
        return response()->json($attendanceService->saveAttendance($request->type,$request->timestamp,$request->id));
    }

    public function employeeAttendance($employee_biometrics_id, AttendanceService $attendanceService)
    {
        return $attendanceService->employeeAttendance($employee_biometrics_id);
    }

    public function allEmployeeAttendance(AttendanceService $attendanceService)
    {
        return $attendanceService->employeeAttendance(null);
    }
}
