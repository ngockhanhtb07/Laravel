<?php


namespace App\Http\Resources\Diary;


use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaryResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        $userId = $this->owner_id;
        $media = $this->medias == null ? [] : MediaResource::collection($this->medias);
        $isPrivate = ($this->is_enabled == 1) ? 0 : 1;
        $ownerCommentIsLiked = (isset($this->created_user)) ? (in_array($this->created_user, $this->likedUsers->modelKeys()) ? 1 : 0) : 0;
        $defaultNotify = (isset($this->created_user) && isset($this->owner_id) && $this->created_user === $this->owner_id) ? 1 : 0;
        if ($this->created_user) {
            $ownerNotifyWatch = $this->notificationUser->where('user_id', $this->created_user)->first();
            $ownerPostIsWatching = $ownerNotifyWatch ? $ownerNotifyWatch->pivot->watch : 1;
        }
        if ($userId) {
            $userNotifyWatch = $this->notificationUser->where('user_id', $userId)->first();
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
        return [
            'id' => $this->format($this->post_id,"integer"),
            'title' => $this->format($this->title),
            'content' => $this->format($this->content),
            'media' => $media,
            'check_save' => in_array($userId, $this->favouritedUsers->modelKeys()) ? 1 : 0,
            'is_liked' => in_array($userId, $this->likedUsers->modelKeys()) ? 1 : 0,
            'owner_post_is_liked' => $ownerCommentIsLiked,
            // notify status of this owner post
            'owner_post_is_watching' => isset($ownerPostIsWatching) ? $ownerPostIsWatching : 1,
            // turnOn: button for "Turn on notify for this post", reverse of current status
            'turnOn' => isset($userIsWatching) ? $userIsWatching : $defaultNotify,
            'like_number' => $this->format($this->likes_count,"integer"),
            'comment_number' => $this->format($this->comment_parents_count,"integer"),
            'total_comment_users' => $totalCommentUsers,
            'comment_user_ids' => $commentUserIds,
            'user_create' => is_null($this->createdUser) ? new \stdClass() : new UserResource($this->createdUser),
            'status' => $this->format($this->status,"integer"),
            'is_private' => $this->format($isPrivate,"integer"),
            'created' => empty($this->created_at) ? "" : strtotime($this->created_at),
        ];
    }

}
