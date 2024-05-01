<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Validator;

class StoreDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add discounts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required',Rule::in(['voucher','coupon'])],
            'value_type' => ['required',Rule::in(['amount','percentage'])],
            'amount' => ['required']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if($this->input('value_type') == 'amount' && $this->input('amount') > 5000){
                $validator->errors()->add(
                    'amount',
                    'Max of 5000 only'
                );
            }
            if($this->input('value_type') == 'percentage' && $this->input('amount') > 100){
                $validator->errors()->add(
                    'amount',
                    'Max of 100 only'
                );
            }
        });
    }
}
