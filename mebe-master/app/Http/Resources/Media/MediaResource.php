<?php

namespace App\Http\Resources\Media;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {

        return [
            'id' => $this->format($this->external_id,"integer"),
            'link' => $this->format($this->link),
            'type_media' => $this->format($this->type),
            'description' => $this->format($this->description),
            "link_ads"=> $this->format($this->link_ads)
        ];
    }
}
