<?php


namespace App\Http\Resources\Diary;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DiaryCollection extends ResourceCollection
{


    public function toArray($request)
    {
        if ($request->request->get('page') > 0) {
            $next_page = 0;
            if ($this->currentPage() < $this->lastPage())
                $next_page = $this->currentPage() + 1;
            return [
                'diaries' => DiaryResource::collection($this->collection),
                'next_page'=>$next_page,
            ];
        }
        else
            return DiaryResource::collection($this->collection);
    }

}