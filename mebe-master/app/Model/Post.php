<?php

namespace App\Model;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    use ElasticquentTrait;
    protected $primaryKey = 'post_id';

    protected $appends = array('total_likes', 'total_comments');

    protected $fillable = [
        'title',
        'quote',
        'slug',
        'content',
        'url_image',
        'author',
        'created_user',
        'updated_user',
        'category_id',
        'is_enabled',
        'status'
    ];

    protected $notFoundMessage = 'The Post could not be found';

    //Set index diary and post elasticsearch
    function getIndexName()
    {
        return 'post_index';
    }


    public function likes() {
        return $this->hasMany(Like::class, 'post_id', 'post_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function commentParents() {
        return $this->comments()->whereNull('comment_parent_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function favourites(){
        return $this->hasMany(FavouritePost::class,'post_id');
    }

    public function medias() {
        return $this->hasMany(Media::class, 'owner_id', 'post_id');
    }


    public function createdUser() {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_user');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'post_attribute', 'post_id',
            'attribute_id')->withTimestamps();
    }

    public function variantAttributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'post_attribute_value', 'post_id',
            'attribute_value_id')->withTimestamps();
    }

    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getTotalCommentsAttribute() {
        return $this->comments()->count();
    }
    public function favouritedUsers() {
        return $this->belongsToMany(User::class, 'favourite_posts', 'post_id', 'user_id');
    }

    public function likedUsers() {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function isFavouritePost($userId) {
        return in_array($userId, $this->favouritedUsers->modelKeys());
    }

    public function isLiked($userId){
        return in_array($userId, $this->likedUsers->modelKeys()) ? 1 : 0;
    }

    public function isDiary() {
        $category = $this->category;
        if ($category && $category->group && $category->group->group_name == 'diary') {
            return true;
        }
        return false;
    }
    public function notificationUser() {
        return $this->belongsToMany(User::class, 'notification_post_user', 'post_id', 'user_id')->withPivot('watch')->withTimestamps();
    }
}
