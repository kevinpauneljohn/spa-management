<?php

namespace App\Services\PointOfSales;

use Illuminate\Http\Request;
use Spatie\Activitylog\Contracts\Activity;

class Appointment extends TransactionService
{
    public function saveClient(Request $request)
    {
        if(collect($request->all())->has('client_id'))
        {
            $appointment = $this->saveOldClientToAppointment($request);
            $this->logActivity($appointment);
        }else{
            $appointment = $this->saveNewClientToAppointments($request);
            $this->logActivity($appointment);
        }
        return $appointment;
    }

    private function saveOldClientToAppointment($request)
    {
        $clientId = $request->client_id;
        return \App\Models\Appointment::create($this->dataFormatted($request, $clientId));
    }

    private function saveNewClientToAppointments($request)
    {
        if($this->clientExistsFromTheClientsTable($request))
        {
            $client = $this->client($request)->first();
        }
        else{
            $client = $this->storeClientToTheClientsTable($request);

            $this->saveClientOwnerRelationship($client->id, $this->owner->id);
        }

        return \App\Models\Appointment::create($this->dataFormatted($request, $client->id));
    }

    private function dataFormatted($request, $clientId): array
    {
        return [
            'spa_id' => $request->spa_id,
            'client_id' => $clientId,
            'appointment_date' => $request->appointment_date,
            'remarks' => $request->remarks,
            'user_id' => auth()->user()->id
        ];
    }

    private function logActivity($appointment)
    {
        activity()->causedBy(auth()->user()->id)
            ->withProperties(collect($appointment)
                ->merge([
                    'table' => 'appointments',
                    'caused_by' => auth()->user()->id,
                    'causer_name' => auth()->user()->fullname,
                    'client_name' => $appointment->client->full_name,
                ]))
            ->tap(function(Activity $activity) use ($appointment){
                $activity->spa_id = $appointment->spa_id;
            })
            ->log('created an appointment');
    }
}
