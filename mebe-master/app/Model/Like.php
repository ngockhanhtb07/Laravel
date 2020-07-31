<?php

namespace App\Model;

use App\Model\User;
use App\Model\Post;
use App\Model\Comment;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'comment_id',
        'type',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class,'post_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class,'comment_id');
    }
}
