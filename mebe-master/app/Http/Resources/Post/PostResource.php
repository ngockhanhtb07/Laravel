<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;


/**
 * @method isFavouritePost($user_id)
 * @method isLiked($user_id)
 * @property mixed created_user
 * @property mixed updated_user
 * @property mixed updatedUser
 * @property mixed createdUser
 */
class PostResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $create = is_null($this->createdUser) ? null : $this->createdUser;
        $update = is_null($this->updatedUser) ? null : $this->updatedUser;
        $category = $this->category;
        // owner_id : owner of action like, comment with post, not user created post
        $isLiked = (isset($this->owner_id)) ? (in_array($this->owner_id, $this->likedUsers->modelKeys()) ? 1 : 0) : 0 ;
        $ownerCommentIsLiked = (isset($this->created_user)) ? (in_array($this->created_user, $this->likedUsers->modelKeys()) ? 1 : 0) : 0;
        $defaultNotify = (isset($this->created_user) && isset($this->owner_id) && $this->created_user === $this->owner_id) ? 1 : 0;
        if ($this->created_user) {
            $ownerNotifyWatch = $this->notificationUser->where('user_id', $this->created_user)->first();
            $ownerPostIsWatching = $ownerNotifyWatch ? $ownerNotifyWatch->pivot->watch : $defaultNotify;
        }
        if ($this->owner_id) {
            $userNotifyWatch = $this->notificationUser->where('user_id', $this->owner_id)->first();
            $userIsWatching = $userNotifyWatch ? $userNotifyWatch->pivot->watch : $defaultNotify;
        }
        $commentUserIds = [];
        $totalCommentUsers = 0;
        $comments = $this->comments;
        if ($comments) {
            $totalCommentUsers = $comments->unique('user_id')->count();
            foreach ($comments->unique('user_id') as $comment) {
                $user = $comment->user;
                $watchNotify = $user->notificationPost->find($this->post_id);
                $commentUserIds[] = [
                    'user_id' => $user->external_id,
                    'watch' => $watchNotify ? $watchNotify->pivot->watch : 1
                ];
            }
        }
        /** @var Object $this */
        return [
            'id' => $this->post_id,
            'content' => $this->format($this->content),
            'title' => $this->format($this->title),
            'slug' => $this->format($this->slug),
            'url_image' => $this->format($this->url_image),
            'author' => $this->format($this->author),
            'quote' => $this->format($this->quote),
            'like_number' => $this->format($this->likes_count,"integer"),
            'comment_number' => $this->format($this->comment_parents_count,"integer"),
            'total_comments_user' => $totalCommentUsers,
            'comment_user_ids' => $commentUserIds,
            'check_save' => in_array($this->owner_id, $this->favouritedUsers->modelKeys()) ? 1 : 0,
            'is_liked' => $isLiked,
            'owner_post_is_liked' => $ownerCommentIsLiked,
            // notify status of this owner post
            'owner_post_is_watching' => isset($ownerPostIsWatching) ? $ownerPostIsWatching : $defaultNotify,
            // turnOn: button for "Turn on notify for this post", reverse of current status
            'turnOn' => isset($userIsWatching) ? $userIsWatching : 1,
            'category_id' => empty($category) ? 0 : $category->category_id,
            'user_create' => is_null($create) ? new stdClass() : new UserResource($create) ,
            'user_update' => is_null($update) ? new  stdClass() : new UserResource($update),
            'created' => empty($this->created_at) ? 0: strtotime($this->created_at),
            'updated' => empty($this->updated_at) ? 0 : strtotime($this->updated_at)
        ];
    }
}
