<?php


namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return OrderItemResource::collection($this->collection);
    }
}