<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasAnyRole(['front desk','manager','owner']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_money' => 'required'
        ];
    }
}
