<?php

namespace App\Http\Requests;

use App\Models\Therapist;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class TherapistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add therapist') || auth()->user()->can('edit therapist');
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator) {

                if($this->input('offer_type') ==='percentage_plus_allowance')
                {
                    if($this->input('commission_percentage') === null && $this->input('allowance') === null)
                    {
                        $validator->errors()->add('commission_percentage', 'commission percentage field is required')
                            ->add('allowance', 'allowance field is required');
                    }
                    elseif ($this->input('commission_percentage') === null)
                    {
                        $validator->errors()->add('commission_percentage', 'commission percentage field is required');
                    }
                    elseif ($this->input('allowance') === null)
                    {
                        $validator->errors()->add('allowance', 'allowance field is required');
                    }
                }
                elseif ($this->input('offer_type') === 'percentage_only')
                {
                    if($this->input('commission_percentage') === null)
                    {
                        $validator->errors()->add('commission_percentage', 'commission percentage field is required');
                    }
                }
                elseif ($this->input('offer_type') === 'amount_plus_allowance')
                {
                    if($this->input('commission_flat') === null && $this->input('allowance') === null)
                    {
                        $validator->errors()->add('commission_flat', 'commission amount field is required')
                            ->add('allowance', 'allowance field is required');
                    }
                    elseif ($this->input('commission_flat') === null)
                    {
                        $validator->errors()->add('commission_flat', 'commission amount field is required');
                    }
                    elseif ($this->input('allowance') === null)
                    {
                        $validator->errors()->add('allowance', 'allowance field is required');
                    }
                }
                elseif ($this->input('offer_type') === 'amount_only')
                {
                    if ($this->input('commission_flat') === null)
                    {
                        $validator->errors()->add('commission_flat', 'commission amount field is required');
                    }
                }
            }

            if($this->isMethod('post'))
            {
                if(User::where('email', $this->input('email'))->count() > 0) $validator->errors()->add('email', 'The email has already been taken!');
                if(User::where('mobile_number', $this->input('mobile_number'))->count() > 0) $validator->errors()->add('mobile_number', 'The mobile number has already been taken!');


            }
            elseif ($this->isMethod('patch'))
            {
                $user = Therapist::findOrFail($this->input('therapistId'));
                if(User::whereNotIn('id',[$user->user_id])->where('email', $this->input('email'))->count() > 0) $validator->errors()->add('email', 'The email has already been taken!');
                if(User::whereNotIn('id',[$user->user_id])->where('mobile_number', $this->input('mobile_number'))->count() > 0) $validator->errors()->add('mobile_number', 'The mobile number has already been taken!');

            }

            if(!empty($this->input('email')))
            {
                if(!filter_var($this->input('email'), FILTER_VALIDATE_EMAIL))
                {
                    $validator->errors()->add('email', 'Must be a valid email!');
                }
            }

        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'offer_type' => 'required',
            'commission_percentage' => 'min:0|max:100'
        ];
    }
}
