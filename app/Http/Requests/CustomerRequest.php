<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $customer_id= $this->route()->parameter('customer_id');
        if($customer_id){
            $email='required|unique:customers,email,'.$customer_id.',customer_id';
        }else{
            $email='required|unique:customers,email';
        }
        //dd($customer_id);

        return [
            "name" => "required|min:3",
            "email" => $email ,
            "phone" => "required|min:10|numeric"
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'email.required' => 'Email is required!',
            'email.email' => 'Not email format',
            'email.unique' => 'Email is used',
            'phone' => 'Phone is required!'
        ];
    }
}
