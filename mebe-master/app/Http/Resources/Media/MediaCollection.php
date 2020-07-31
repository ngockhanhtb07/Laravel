<?php


namespace App\Http\Resources\Media;


use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends ResourceCollection
{
    use FormatResponse;

    public function toArray($request)
    {
        return MediaResource::collection($this->collection);
    }
}