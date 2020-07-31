<?php

namespace App\Http\Resources\Cart;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCartCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return ItemCartResource::collection($this->collection);

    }
}
