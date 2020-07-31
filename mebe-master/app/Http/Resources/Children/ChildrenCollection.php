<?php

namespace App\Http\Resources\Children;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChildrenCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $next_page = 0;
        if ($this->currentPage() < $this->lastPage()) {
            $next_page = $this->currentPage() + 1;
        }
        return [
            'data' => ChildrenResource::collection($this->collection),
            'next_page'=> $next_page,
        ];
    }
}
