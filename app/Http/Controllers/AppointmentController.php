<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Spa;
use Carbon\Carbon;
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

    public function checkBatch($id, $batch): bool
    {
        return $this->appointmentService->checkBatch($id, $batch);
    }
    public function getResponses($id): array
    {
        return $this->appointmentService->getAppointmentResponses($id);
    }

    public function getCalendarEvents(Spa $spa): \Illuminate\Support\Collection
    {
        $appointments = collect($spa->appointments()->whereBetween('created_at', [
            Carbon::now()->subMonths(6),
            Carbon::now()->addMonths(6)
        ])
            ->get())->toArray();

        $transactions = collect($spa->transactions()->whereBetween('created_at', [
            Carbon::now()->subMonths(6),
            Carbon::now()->addMonths(6)
        ])
        ->get())->toArray();

        $calendarBookings = collect($appointments)->merge($transactions)->toArray();

        return collect($calendarBookings)->mapWithKeys(function($item, $key){
            return [
                $key => [
                    'id' => $item['id'],
                    'title' => Client::find($item['client_id'])->fullName,
                    'start' => Carbon::parse($item['appointment_date']),
                    'allDay' => false,
                    'color' => collect($item)->has('sales_id') ? '#28a745' : '#ff2f25',
                    'category' => collect($item)->has('sales_id') ? 'completed' : 'upcoming',
                ]
            ];
        });
    }
}
