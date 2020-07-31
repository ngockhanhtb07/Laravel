<?php

namespace App\Http\Resources\Shipping;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingResource extends JsonResource
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
            'shipping_id' => $this->format($this->vendor_id,"integer"),
            'name' => $this->format($this->name),
            'shipping_fee' => 40000,
        ];
    }
}
