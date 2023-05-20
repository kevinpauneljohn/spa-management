<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\AppointmentService;
use Config;

class AppointmentController extends Controller
{
    private $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        // $this->middleware(['check.if.user.is.receptionist'])->only(['getAppointmentType', 'store', 'show', 'edit', 'destroy']);

        $this->appointmentService = $appointmentService;
    }

    public function lists($id)
    {
        return $this->appointmentService->data($id);
    }

    public function store(Request $request, $id)
    {
        return $this->appointmentService->create($request, $id);
    }

    public function storeSales(Request $request, $id, $amount)
    {
        return $this->appointmentService->appointmentCreateSales($request, $id, $amount);
    }

    public function show($id)
    {
        return $this->appointmentService->view($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return $this->appointmentService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->appointmentService->delete($id);
    }

    public function getAppointmentType()
    {
        $appointment_type = Config::get('app.appointment_type');
        $social_media_type = Config::get('app.social_media_type');

        $response = [
            'appointment_type'   => $appointment_type,
            'social_media'   => $social_media_type
        ]; 

        return $response;
    }

    public function upcoming($id)
    {
        return $this->appointmentService->getUpcoming($id);
    }

    public function sales(Request $request)
    {
        return $this->appointmentService->appointmentSales($request);
    }

    public function getUpcomingGuests($id)
    {
        return $this->appointmentService->getUpcomingGuest($id);
    }

    public function getResponses($id)
    {
        return $this->appointmentService->getAppointmentResponses($id);
    }
}
