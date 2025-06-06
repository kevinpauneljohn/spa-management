<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('edit employee');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable','string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['nullable','string', 'email', 'max:255'],
            'username' => ['nullable','string','max:255'],
            'role' => ['required'],
            'spa_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'spa_id.required' => 'The SPA field is required.',
        ];
    }
}
