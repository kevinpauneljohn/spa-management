<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasAnyPermission(['view appointment','add appointment','edit appointment','delete appointment']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'firstname' => ['max:100',Rule::requiredIf(!collect($request->all())->has('client_id'))],
            'middlename' => ['max:100'],
            'lastname' => ['max:100',Rule::requiredIf(!collect($request->all())->has('client_id'))],
            'date_of_birth' => ['nullable','date'],
            'email' => ['nullable','email'],
            'appointment_date' => ['required','date']
        ];
    }
}
