<?php

namespace App\Http\Resources\Address;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'address_id' => $this->format($this->address_id,"integer"),
            'first_name' => $this->format($this->first_name),
            'last_name' => $this->format($this->last_name),
            'phone' => $this->format($this->phone),
            'province' => $this->format($this->province),
            'city' => $this->format($this->city),
            'district' => $this->format($this->district),
            'ward' => $this->format($this->ward),
            'street' => $this->format($this->street),
            'is_default' => $this->format($this->is_default,"integer"),
        ];
    }
}
