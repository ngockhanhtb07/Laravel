<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return OrderResource::collection($this->collection);
    }

}
