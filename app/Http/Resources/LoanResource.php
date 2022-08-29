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
            'amount' => number_format($this->amount, 2, ".", ""),
            'term'=> $this->term,
            'loan_status'=> $this->loan_status,
            'total_paid'=> number_format($this->total_paid, 2, ".", ""),
            'min_payment'=>  number_format($this->min_payment, 2, ".", ""),
            'installments'=>  InstallmentResource::collection($this->installments)
        ];
    }
}
