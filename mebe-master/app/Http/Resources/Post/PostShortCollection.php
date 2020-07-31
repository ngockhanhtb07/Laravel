<?php


namespace App\Http\Resources\Post;


use Illuminate\Http\Resources\Json\ResourceCollection;

class PostShortCollection extends ResourceCollection
{
    public function toArray($request)
    {

        $next_page = 0;
        if ($this->currentPage() < $this->lastPage()) {
            $next_page = $this->currentPage() + 1;
        }
        $data = [
            'posts' => PostShortResource::collection($this->collection),
            'next_page' => $next_page,
        ];
        return $data;


    }
}