<?php

namespace App\Http\Resources\Post\CMS;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return PostResource::collection($this->collection);
    }
}
