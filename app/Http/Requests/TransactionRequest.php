<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
        if($this->has('transaction_id')){
            return $this->rulesWithTransactionID();
        }else{
            return $this->rulesWithoutTransactionID();
        }

    }
    private function rulesWithTransactionID(){
        return [
            "transaction_id"=>'required:exists:transactions,transaction_id',
            "amount"=>'required|numeric|gt:0'
        ];
    }
    private function rulesWithoutTransactionID(){
        return [
            "amount"=>'required|numeric|gt:0'
        ];
    }

    public function messages()
    {
        return [
            'transaction_id.required' => 'transaction_id is required!',
            'transaction_id.exists' => 'transaction_id is not exists in DB!',
            'amount.required' => 'Amount is required!',
            'amount.numeric' => 'Amount must be numeric',
            'amount.gt' => 'Amount must be more than 0!'
        ];
    }
}
