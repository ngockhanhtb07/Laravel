<?php


namespace App\Http\Resources\Diary;


use Illuminate\Http\Resources\Json\ResourceCollection;

class DiaryHomeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        if ($request->request->get('page') > 0) {
            $next_page = 0;
            if ($this->currentPage() < $this->lastPage()) {
                $next_page = $this->currentPage() + 1;
            }
            return [
                'diaries' => DiaryHomeResource::collection($this->collection),
                'next_page' => $next_page,
                'total_page' =>$this->lastPage()
            ];
        } else {
            return DiaryHomeResource::collection($this->collection);
        }
    }

}
