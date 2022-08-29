<?php

namespace App\Http\Resources;
use App\Http\Resources\InstallmentResource;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'amount' => $this->amount,
            'term'=> $this->term,
            'loan_status'=> $this->loan_status,
            'total_paid'=> $this->total_paid,
            'min_payment'=> $this->min_payment,
            'installments'=>  InstallmentResource::collection($this->installments)
        ];
    }
}
