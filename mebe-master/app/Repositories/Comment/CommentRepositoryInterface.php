<?php

namespace App\Repositories\Comment;

interface CommentRepositoryInterface {
    public function findByPost($post_id, $user_id);
    public function findByParent($post_id,$user);
    public function findByPostAndUser($user_id,$comment_id);
    public function delete($id);
}