<?php

namespace App\Http\Resources\ReplyComment;

use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyCommentResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->user;
        $isLiked = 0;
        if (!empty($this->owner_id)) {
            $isLiked = (isset($this->owner_id)) ? (in_array($this->owner_id, $this->likedUsers->modelKeys()) ? 1 : 0) : 0 ;
        }
        $ownerCommentIsLiked = (isset($this->user_id)) ? (in_array($this->user_id, $this->likedUsers->modelKeys()) ? 1 : 0) : 0 ;
        $userTag = empty($this->user_tagged) ? [] : [new UserResource($this->userTagged)];
        if ($user) {
            $ownerNotifyWatch = $user->notificationPost->find($this->post_id);
            $ownerReplyIsWatching = $ownerNotifyWatch ? $ownerNotifyWatch->watch : 1;
        }
        return [
            'comment_id' => $this->format($this->comment_id,"integer"),
            'content' => empty($this->content) ? "" : $this->content,
            'created' => empty($this->created_at) ? 0 : strtotime($this->created_at),
            'user' => new UserResource($user),
            'is_liked' => $isLiked,
            'owner_reply_is_liked' => $ownerCommentIsLiked,
            'owner_reply_is_watching' => isset($ownerReplyIsWatching) ? $ownerReplyIsWatching : 1,
            'like_number' => $this->format($this->get_likes_count,"integer"),
            'user_tag' => $userTag,
        ];
    }
}
