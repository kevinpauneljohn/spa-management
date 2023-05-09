<?php

namespace App\Http\Requests;

use App\Models\InventoryCategory;
use Illuminate\Foundation\Http\FormRequest;

class InventoryCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('add category') || auth()->user()->can('edit category');
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if($this->isMethod('post'))
            {
                if(InventoryCategory::where('name',$this->input('name'))->count() > 0)
                    $validator->errors()->add('name', 'The category has already been taken!');
            }
            elseif ($this->isMethod('patch'))
            {
                if(InventoryCategory::whereNotIn('id',[$this->input('id')])->where('name',$this->input('name'))->count() > 0)
                    $validator->errors()->add('name', 'The category has already been taken!');
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
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category field is required',
        ];
    }
}
