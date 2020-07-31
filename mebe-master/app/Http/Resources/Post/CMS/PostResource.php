<?php

namespace App\Http\Resources\Post\CMS;

use App\Http\Resources\AttributeValue\AttributeValueResource;
use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

class PostResource extends JsonResource
{
    use FormatResponse;
    public function toArray($request)
    {
        $create = is_null($this->created_user) ? null : $this->createdUser;
        $update = is_null($this->updated_user) ? null : $this->updatedUser;
        $category = $this->category;
        $favourite = (isset($this->user_id)) ? $this->format($this->isFavouritePost($this->user_id),"integer") : 0 ;
        $isLiked = (isset($this->user_id)) ? $this->format($this->isLiked($this->user_id),"integer") : 0 ;
        /** @var Object $this */
        return [
            'post_id' => $this->post_id,
            'content' => $this->format($this->content),
            'title' => $this->format($this->title),
            'quote' => $this->format($this->quote),
            'slug' => $this->format($this->slug),
            'url_image' => $this->format($this->url_image),
            'author' => $this->format($this->author),
            'like_number' => $this->format($this->total_likes,"integer"),
            'comment_number' => $this->format($this->total_comments,"integer"),
            'is_favourite' => $favourite,
            'is_liked' => $isLiked,
            'is_enabled' => $this->format($this->is_enabled,"integer"),
            'status' => $this->format($this->status,"integer"),
            'category' => empty($category) ? null : $category,
            'attribute' => is_null($this->variantAttributes) ? [] : AttributeValueResource::collection($this->variantAttributes),
            'created_user' => is_null($create) ? new stdClass() : new UserResource($create),
            'updated_user' => is_null($update) ? new  stdClass() : new UserResource($update),
            'created' => empty($this->created_at) ? 0: strtotime($this->created_at),
            'updated' => empty($this->updated_at) ? 0 : strtotime($this->updated_at)
        ];
    }
}
