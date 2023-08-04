<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add appointment'])->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAppointmentRequest $request, \App\Services\PointOfSales\Appointment $appointmentService): \Illuminate\Http\JsonResponse
    {
        $appointment = $appointmentService->saveClient($request);
        return response()->json([
            'success' => true,
            'message' => 'Appointment Successfully created!',
            'appointment' => $appointment]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Appointment $appointment
     * @param \App\Services\PointOfSales\Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, \App\Services\PointOfSales\Appointment $appointment): \Illuminate\Http\JsonResponse
    {
        return $appointment->rescheduleAppointment($id, $request['appointmentDate']) ?
            \response()->json(['success' => true, 'message' => 'Appointment Successfully Rescheduled'])
            : \response()->json(['success' => false, 'message' => 'No changes made']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Appointment $appointment): \Illuminate\Http\JsonResponse
    {
        return $appointment->delete() ? response()->json(['success' => true, 'message' => 'Booking successfully deleted'])
            : response()->json(['success' => false, 'message' => 'An error occurred!']);
    }
}
