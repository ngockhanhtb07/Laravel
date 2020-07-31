<?php

namespace App\Http\Resources\Attribute;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AttributeCollection extends ResourceCollection {

    public function toArray($request)
    {
        return AttributeResource::collection($this->collection);
    }
}