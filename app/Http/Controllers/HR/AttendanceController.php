<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\HR\AttendanceService;
use App\Services\UserService;
use Carbon\Carbon;
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
            collect($request->all())->toArray()
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(Attendance::findOrFail($id)->delete())
        {
            return response()->json(['success' => true,'message' => 'Attendance deleted successfully.']);
        }
        return response()->json(['success' => false,'message' => 'A error occurred']);
    }

    public function storeAttendance(Request $request, AttendanceService $attendanceService): \Illuminate\Http\JsonResponse
    {

        return response()->json($attendanceService->saveAttendance($request->type,$request->timestamp,$request->id));
    }

    public function addNewEmployeeAttendance(StoreNewAttendanceRequest $request, AttendanceService $attendanceService): \Illuminate\Http\JsonResponse
    {
        return response()->json($attendanceService->save_employee_attendance_manually(
            collect($request->all())->toArray()
        ));
    }
    public function employeeAttendance(Request $request, $employee_biometrics_id, AttendanceService $attendanceService)
    {
        return $attendanceService->employeeAttendance($request, $employee_biometrics_id);
    }

    public function allEmployeeAttendance(Request $request, AttendanceService $attendanceService)
    {
        return $attendanceService->employeeAttendance($request,null);
    }

    public function get_attendance_by_date_range(Request $request)
    {

        $date = explode('-',$request->input('date'));
        $startDate = Carbon::parse($date[0])->startOfDay();
        $endDate = Carbon::parse($date[1])->endOfDay();

        $request->session()->put('attendance_start_date',$startDate);
        $request->session()->put('attendance_end_date',$endDate);
    }
}
