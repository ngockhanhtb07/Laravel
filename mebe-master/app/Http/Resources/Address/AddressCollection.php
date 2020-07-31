<?php


namespace App\Http\Resources\Address;


use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'address' => AddressResource::collection($this->collection)
        ];
    }

}