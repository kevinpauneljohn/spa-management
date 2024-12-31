<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\HR\ScheduleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware(['permission:view schedule'])->only(['index','displaySchedules']);
        $this->middleware(['permission:add schedule'])->only('store');
        $this->middleware(['permission:edit schedule'])->only(['edit','update']);
        $this->middleware(['permission:delete schedule'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('hr.schedules.index');
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
     * @param StoreScheduleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreScheduleRequest $request, ScheduleService $scheduleService)
    {
        $store_schedule_response = $scheduleService->saveSchedule(
            $request->name,
            $request->time_in,
            $request->time_out,
            $request->break_in,
            $request->break_out,
            $this->userService->get_staff_owner()->id,
            auth()->user()->id,
        );

        return response()->json($store_schedule_response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return Schedule
     */
    public function edit(Schedule $schedule)
    {
        return $schedule;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateScheduleRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateScheduleRequest $request, $id, ScheduleService $scheduleService): \Illuminate\Http\JsonResponse
    {
        return response()->json($scheduleService->updateSchedule(
            $id, $request->name, $request->time_in, $request->time_out, $request->break_in, $request->break_out, \auth()->user()->id
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Schedule $schedule): \Illuminate\Http\JsonResponse
    {
        if($schedule->delete())
        {
            return response()->json(['success' => true, 'message' => 'Schedule deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Schedule could not be deleted.']);
    }

    /**
     * @param ScheduleService $scheduleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function displaySchedules(ScheduleService $scheduleService): \Illuminate\Http\JsonResponse
    {
        return $scheduleService->getSchedules($this->userService->get_staff_owner()->id);
    }
}
