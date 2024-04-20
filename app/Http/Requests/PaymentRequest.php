<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('process payment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'non_cash_amount' => [Rule::requiredIf($request->payment_type !== 'Cash'),'min:0','max:'.$request->total_service_amount,'numeric','nullable'],
            'cash' => [Rule::requiredIf($request->payment_type === 'Cash'),'min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator){
            if($this->input('payment_type') !== 'Cash')
            {
                if($this->totalAmountPaid() < $this->input('total_service_amount'))
                {
                    $validator->errors()->add('non_cash_amount', 'Your payment is less than the amount due');
                }
            }
            else{
                if($this->cash() < $this->input('total_service_amount'))
                {
                    $validator->errors()->add('cash', 'Your payment is less than the amount due');
                }
            }
        });
    }

    private function nonCash()
    {
        return $this->input('non_cash_amount');
    }

    private function cash()
    {
        $cash = 0;
        if(collect($this->input())->has('cash'))
        {
            if(!is_null($this->input('cash')))
            {
                $cash = $this->input('cash');
            }
        }

        return $cash;
    }

    private function totalAmountPaid()
    {
        return $this->nonCash() + $this->cash();
    }
}
