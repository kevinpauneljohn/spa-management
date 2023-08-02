<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class SalesTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'firstname' => Rule::requiredIf(!collect($request->all())->has('client_id')),
            'lastname' => Rule::requiredIf(!collect($request->all())->has('client_id')),
            'client_id' => Rule::requiredIf(collect($request->all())->has('client_id')),
            'email' => ['email','nullable'],
            'service' => Rule::requiredIf($request->appointment_type === 'Walk-in'),
            'preparation_time' => Rule::requiredIf($request->appointment_type === 'Walk-in'),
            'room' => ['required'],
            'therapist_1' => ['required'],
            'service_id' => ['required'],
            'therapist_2' => Rule::requiredIf($request->service !== null && Service::find($request->service_id)->multiple_masseur),
        ];
    }
}
