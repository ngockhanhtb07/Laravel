<?php


namespace App\Http\Resources\ReplyComment;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ReplyCommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $next_page = 0;
        if ($this->currentPage() < $this->lastPage()) {
            $next_page = $this->currentPage() + 1;
        }
        return [
            'list_reply_comment' => ReplyCommentResource::collection($this->collection),
            'next_page'=>$next_page,
        ];
    }
}