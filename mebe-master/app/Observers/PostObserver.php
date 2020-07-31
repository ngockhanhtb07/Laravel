<?php

namespace App\Observers;

use App\Model\Post;
use App\Repositories\PostHistory\PostHistoryRepository;
use App\Traits\CommonResponse;
use Carbon\Carbon;

class PostObserver
{
    use CommonResponse;
    protected $_postHistory;
    public function __construct(PostHistoryRepository $postHistory)
    {
        $this->_postHistory = $postHistory;
    }

    public function saving(Post $post) {
        if (!$post->isDirty($post->getFillable())) {
            return false;
        }
        return true;
    }
    public function saved(Post $post) {
        $postHistory = $post->only('post_id', 'title', 'quote', 'slug', 'content', 'author', 'category_id', 'status');
        foreach ($postHistory as $key => $value){
            if (is_null($value)){
                unset($postHistory[$key]);
            }
        }
        $postHistory['time_id'] = Carbon::now()->toDateTimeString();
        $postHistory['created_user'] = $post->updated_user ?? $post->created_user;
    }
    /**
     * Handle the post "deleting" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function deleting(Post $post)
    {
        $post->comments()->delete();
        $post->favourites()->delete();
        $post->variantAttributes()->detach();
        $post->attributes()->detach();
        $post->likes()->delete();
        $post->medias()->delete();
    }

}
