<?php

namespace App\Http\Resources\Payment;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'payment_id' => $this->format($this->payment_id,"integer"),
            'payment_name' => $this->format($this->name),
        ];
    }
}
