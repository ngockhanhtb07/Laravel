<?php


namespace App\Http\Resources\Shipping;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ShippingCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return ShippingResource::collection($this->collection);
    }

}