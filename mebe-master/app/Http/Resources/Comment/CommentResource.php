<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\ReplyComment\ReplyCommentResource;
use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\Comment;

class CommentResource extends JsonResource
{
    use FormatResponse;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $replyCmt = $this->children_count;
        $replyComment = Comment::where('comment_parent_id', $this->comment_id)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();
        $replyComment->loadCount('likedUsers', 'getLikes')
            ->load(['userTagged', 'user']);
        $sizeRep = count($replyComment);
        if ($sizeRep > 0) {
            foreach ($replyComment as $cmt) {
                $cmt->setAttribute('owner_id', $this->owner_id);
            }
        }
        $isLiked = 0;
        if (!empty($this->owner_id)) {
            $isLiked = (isset($this->owner_id)) ? (in_array($this->owner_id,
                $this->likedUsers->modelKeys()) ? 1 : 0) : 0;
        }
        $ownerCommentIsLiked = (isset($this->user_id)) ? (in_array($this->user_id,
            $this->likedUsers->modelKeys()) ? 1 : 0) : 0;
        $totalReplyUsers = 0;
        $replyUserIds = [];
        $replyComments = $this->children;
        if ($replyComments) {
            $totalReplyUsers = $replyComments->unique('user_id')->count();
            foreach ($replyComments->unique('user_id') as $reply) {
                $user = $reply->user;
                $watchNotify = $user->notificationPost->find($this->user->user_id);
                $replyUserIds[] = [
                    'user_id' => $user->external_id,
                    'watch' => $watchNotify ? $watchNotify->watch : 1
                ];
            }
        }
        if ($this->user) {
            $ownerNotifyWatch = $this->user->notificationPost->find($this->post_id);
            $ownerCommentIsWatching = $ownerNotifyWatch ? $ownerNotifyWatch->watch : 1;
        }
        return [
            'comment_id' => $this->format($this->comment_id, "integer"),
            'content' => empty($this->content) ? "" : $this->content,
            'created' => empty($this->created_at) ? "" : strtotime($this->created_at),
            'updated' => empty($this->updated_at) ? "" : strtotime($this->updated_at),
            'user' => empty($this->user) ? new \stdClass() : new UserResource($this->user),
            'like_number' => $this->format($this->get_likes_count, "integer"),
            'remain_reply' => (($replyCmt - 3) < 0) ? 0 : $replyCmt - 3,
            'reply_comment_number' => $this->format($replyCmt, "integer"),
            'total_reply_users' => $this->format($totalReplyUsers, "integer"),
            'owner_comment_is_watching' => isset($ownerCommentIsWatching) ? $ownerCommentIsWatching : 1,
            'reply_user_ids' => $replyUserIds,
            'is_liked' => $this->format($isLiked, "integer"),
            'owner_comment_is_liked' => $ownerCommentIsLiked,
            'reply' => ReplyCommentResource::collection($replyComment),
            'user_tag' => empty($this->user_tagged) ? [] : [new UserResource($this->userTagged)],
        ];
    }
}
