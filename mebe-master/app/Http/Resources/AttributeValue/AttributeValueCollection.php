<?php

namespace App\Http\Resources\AttributeValue;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AttributeValueCollection extends ResourceCollection {

    public function toArray($request)
    {
        return AttributeValueResource::collection($this->collection);
    }
}