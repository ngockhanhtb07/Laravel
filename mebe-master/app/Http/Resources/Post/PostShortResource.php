<?php


namespace App\Http\Resources\Post;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method isLiked($user_id)
 * @property mixed post_id
 * @property mixed url_image
 * @property mixed title
 * @property mixed total_likes
 * @property mixed total_comments
 */
class PostShortResource extends JsonResource
{
    use FormatResponse;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // owner_id : owner of action like, comment with post, not user created post
        $isLiked = (isset($this->owner_id)) ? (in_array($this->owner_id, $this->likedUsers->modelKeys()) ? 1 : 0) : 0 ;
        return [
            'id' => $this->format($this->post_id,"integer"),
            'title' => $this->format($this->title),
            'quote' => $this->format($this->quote),
            'url_image' => $this->format($this->url_image),
            'is_liked' => $isLiked,
            'like_number' => $this->format($this->likes_count,"integer"),
            'comment_number' => $this->format($this->comment_parents_count,"integer"),
            'created' => empty($this->updated_at) ? 0 : strtotime($this->updated_at)
        ];
    }

}
