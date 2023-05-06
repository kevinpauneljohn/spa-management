<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add inventory') || auth()->user()->can('edit inventory');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spa_id' => 'required',
            'name' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'spa_id.required' => 'The spa field is required',
        ];
    }
}
