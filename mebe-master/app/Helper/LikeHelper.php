<?php
namespace App\Helper;

use App\Enums\CommentType;
use App\Http\Resources\Diary\DiaryResource;
use App\Http\Resources\ReplyComment\ReplyCommentResource;
use App\Model\Comment;
use App\Model\Post;
use App\Enums\LikeType;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Comment\CommentResource;

class LikeHelper extends Helpers {

    public function formatNotification($userFromId, $id, $type, $isLike, $ownerId) {
        // userFromId: external_id, id user from gateway
        // owner_id: id user from shop
        $notificationData = [
            'user_from' => $userFromId,
            'type' => (int) $type,
            'is_like' => $isLike
        ];
        $isDiary = false;
        if (in_array($type, [LikeType::COMMENT, LikeType::REPLY_COMMENT, CommentType::COMMENT, CommentType::REPLY_COMMENT])) {
            $comment = Comment::findOrFail($id);
            $comment->load(['likedUsers','user'])->loadCount(['getLikes','children']);
            // set owner id for owner action
            $comment->setAttribute('owner_id', $ownerId);
            $post = $comment->post;
            $post->setAttribute('owner_id', $ownerId);
            $post->loadCount('likes');
            $notifyContent = [
                'post' => new PostResource($post),
                'comment' => new CommentResource($comment),
                'reply_comment' => []
            ];
            if ($post) {
                $notificationData['user_to'] = $post->createdUser->external_id;
                $notificationData['post_id'] = $post->post_id;
                if ($post->isDiary()) {
                    $isDiary = true;
                    $notifyContent['post'] = new DiaryResource($post);
                }
            }
            $notificationData['comment_id'] = $id;
            if ($type == LikeType::REPLY_COMMENT || $type == CommentType::REPLY_COMMENT) {
                // reply comment
                $notificationData['reply_comment'] = $id;
                $commentParent = $comment->parent;
                $commentParent->setAttribute('owner_id', $ownerId);
                $notificationData['comment_id'] = $commentParent->comment_id;
                $notifyContent['comment'] = new CommentResource($commentParent);
                $notifyContent['reply_comment'] = new ReplyCommentResource($comment);
            }
            //set user to
            if ($type == LikeType::COMMENT || $type == LikeType::REPLY_COMMENT) {
                $notificationData['user_to'] = $comment->user->external_id;
            }
            if ($type == CommentType::REPLY_COMMENT) {
                $notificationData['user_to'] = $comment->parent->user->external_id;
            }
            $notificationData['content'] = json_encode($notifyContent);
        }

        if ($type == LikeType::POST) {
            $post = Post::findOrFail($id);
            $post->loadCount('likes');
            $notificationData['post_id'] = $post->post_id;
            $notificationData['user_to'] = $post->createdUser->external_id;
            $notifyContent = [
                'post' => new PostResource($post),
                'comment' => [],
                'reply_comment' => []
            ];
            if ($post->isDiary()) {
                $isDiary = true;
                $notifyContent['post'] = new DiaryResource($post);
            }
            $notificationData['content'] = json_encode($notifyContent);
        }
        // convert to notification type
        // like, comment, reply feed: 1-> 5, diary: 6 - 10, info: 11 - 15
        if ($type > 0 && $type < 6) {
            if ($isDiary) {
                $notificationData['type'] += 5;
            } else {
                $notificationData['type'] += 10;
            }

        }
        return $notificationData;
    }
}
