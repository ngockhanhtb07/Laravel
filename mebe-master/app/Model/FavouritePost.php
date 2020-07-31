<?php

namespace App\Model;


use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class FavouritePost extends Model
{
    use HasCompositePrimaryKey;
    protected $table = 'favourite_posts';
    protected $primaryKey = ['post_id', 'user_id'];
    protected $fillable = ['post_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }


}
