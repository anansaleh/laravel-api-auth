<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'transaction_id' => $this->transaction_id
            , "customer_id" =>$this->customer_id
            , "customer_name" =>$this->customer_name
            , "amount" =>$this->amount
            , "created_at" =>Carbon::parse($this->created_at)->format('d.m.Y')
            , "updated_at" =>Carbon::parse($this->updated_at)->format('d.m.Y')

        ];
    }
}
