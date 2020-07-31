<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = [
        'content',
        'post_id',
        'user_id',
        'comment_parent_id',
        'is_enabled',
        'user_tagged'
    ];
    protected $notFoundMessage = 'The Comment could not be found';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function userTagged()
    {
        return $this->belongsTo(User::class,'user_tagged');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function getLikes()
    {
        return $this->hasMany(Like::class,'comment_id');
    }

    public function parent(){
        return $this->belongsTo('App\Model\Comment','comment_parent_id');
    }

    public function children(){
        return $this->hasMany('App\Model\Comment','comment_parent_id');
    }

    public function likedUsers() {
        return $this->belongsToMany(User::class, 'likes', 'comment_id', 'user_id');
    }

    public function isLiked($userId){

        return in_array($userId, $this->likedUsers->modelKeys());
    }

    public function isValid()
    {
        if ($this->is_enabled == env('ACTIVE_DEFAULT_VALUE', true))
            return true;
        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function($comment) {
            $comment->getLikes()->delete();

            if ($comment->children) {
                $comment->children()->delete();
            }
        });
    }
}
