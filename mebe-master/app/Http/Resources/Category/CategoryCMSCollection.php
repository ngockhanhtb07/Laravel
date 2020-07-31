<?php


namespace App\Http\Resources\Category;


use App\Http\Resources\Category\CategoryCMSResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCMSCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $next_page = 0;
        if ($this->currentPage() < $this->lastPage()) {
            $next_page = $this->currentPage() + 1;
        }
        return [
            'datas' => CategoryCMSResource::collection($this->collection),
            'next_page'=>$next_page,
            'total_page'=>$this->lastPage()
        ];

    }

}
