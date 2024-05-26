<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add spa');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'number_of_rooms' => 'required',
            'category' => 'required'
        ];
    }
}
