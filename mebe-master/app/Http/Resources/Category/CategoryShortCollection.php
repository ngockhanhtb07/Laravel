<?php


namespace App\Http\Resources\Category;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryShortCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return CategoryShortResource::collection($this->collection);

    }

}