<?php


namespace App\Http\Resources\Diary;


use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaryHomeResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        $userId = $this->owner_id;
        // owner_id : owner of action like, comment with post, not user created post
        $media = ($this->medias->count() == 0) ? ($this->url_image ? [$this->toMediaArray($this->url_image)] : []) : MediaResource::collection($this->medias->take(1));
        return [
            'id' => $this->format($this->post_id, "integer"),
            'title' => $this->format($this->title),
            'quote' => $this->format($this->quote),
            'media' => $media,
            'is_liked' => in_array($userId, $this->likedUsers->modelKeys()) ? 1 : 0,
            'like_number' => $this->format($this->likes_count, "integer"),
            'comment_number' => $this->format($this->comment_parents_count, "integer"),
            'user_create' => is_null($this->createdUser) ? new \stdClass() : new UserResource($this->createdUser),
            'status' => $this->format($this->status, "integer"),
            'created' => empty($this->created_at) ? 0 : strtotime($this->created_at),
        ];
    }

    public function toMediaArray($urlImage)
    {
        $mediaObject = new \StdClass();
        $mediaObject->id = 0;
        $mediaObject->description = '';
        $mediaObject->type_media = 'image';
        $mediaObject->link = $urlImage;
        return $mediaObject;
    }

}
