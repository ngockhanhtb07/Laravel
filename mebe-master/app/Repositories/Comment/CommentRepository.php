<?php

namespace App\Repositories\Comment;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\ReplyComment\ReplyCommentCollection;
use App\Model\Comment;
use App\Repositories\EloquentRepository;

class CommentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Comment::class;
    }

    public function findByPost($post_id, $user_id)
    {
        $comments = $this->_model->where([
            ['post_id', $post_id],
            ['is_enabled', 1],
        ])->whereNull('comment_parent_id')->orderBy('created_at', 'DESC')->paginate();
        $comments->load(['likedUsers','user'])->loadCount(['getLikes','children']);
        foreach ($comments as $comment){
            $comment->setAttribute('owner_id', $user_id);
        }
        $data = new CommentCollection($comments);
        return $data;
    }

    public function findByParent($parent_id,$user)
    {
        $comments = $this->_model
            ->where('comment_parent_id',$parent_id)
            ->orderBy('created_at', 'DESC')
//            ->loadCount('likedUsers')
            ->paginate();
        $comments->load(['userTagged','user','likedUsers']);
        foreach ($comments as $comment){
            $comment->setAttribute('owner_id', $user);
        }
        $data = new ReplyCommentCollection($comments);
        return $data;
    }

    public function findByPostAndUser($user_id,$comment_id)
    {
        $data = $this->_model->where([
            ['user_id',$user_id],
            ['comment_id',$comment_id],
            ['is_enabled', 1]
        ])->firstOrFail();
        return $data;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();
            $this->_model->where('comment_parent_id',$id)->delete();
            return true;
        }
        return false;
    }

}