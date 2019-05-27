<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            "customer_id"=> $this->customer_id
            , "name" => $this->name
            , "email" => $this->email
            , "phone" => $this->phone
            , "updated_at" =>Carbon::parse($this->updated_at)->format('d.m.Y')
            , "created_at" =>Carbon::parse($this->created_at)->format('d.m.Y')
        ];
    }
}
