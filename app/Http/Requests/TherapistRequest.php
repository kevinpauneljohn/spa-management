<?php

namespace App\Http\Requests;

use App\Rules\OfferType;
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
            'offer_type' => [
                'required'
                ]
        ];
    }
}
