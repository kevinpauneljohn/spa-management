<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('manage inventory');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action' => ['required'],
            'update_quantity' => ['required','min:0']
        ];
    }
}
