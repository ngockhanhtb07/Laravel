<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Post\PostShortResource;
use App\Model\AttributeValue;
use App\Model\Post;
use App\Model\PostAttributeValue;
use App\Model\User;
use App\Traits\FormatResponse;
use App\Traits\TransformUserGateway;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    use FormatResponse;
    use TransformUserGateway;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }
        $posts = $this->getPosts($request->input('type'), $request->input('attribute'), $this->category_id);
        $postCount = $posts->count();
        $data = [
            'category_id' => $this->format($this->category_id, "integer"),
            'category_name' => $this->format($this->name),
            'url_image' => $this->format($this->url_image),
            'total_post' => $this->format($postCount, "integer"),

        ];
        // neu type cua category = 0 la category nay khong co category con. show bai viet luon
        // check xem co yeu cau filter bai post theo attribute khong
        // neu category type = 1 tuc la category nay co 1 category con nua. chua show post
        if ($this->type == 0) {
            if ($postCount > 0) {
                $limitPost = $posts->forPage(1, 3);
                $userId = User::where('external_id', $request->user_id)->firstOrFail()->user_id;
                foreach ($limitPost as $post) {
                    $post->setAttribute('owner_id', $userId);
                }
                $data['posts'] = PostShortResource::collection($limitPost);
            } else {
                $data['posts'] = [];
            }
        } else {
            if ($this->type == 1) {
                $child = new CategoryCollection($this->children);
                $data['children'] = is_null($child) ? new  \stdClass() : $child;
            }
        }
        return $data;
    }


    protected function getPosts($type, $attribute, $id_category)
    {
        if ($type == 2) {
            $data = Post::where('category_id', $id_category)
                ->where('is_enabled', 1)
                ->whereHas('variantAttributes', function (Builder $query) use ($attribute) {
                    $query->where('value', $attribute);
                })->orderBy('updated_at', 'desc')->get();
            return $data->loadCount(['likes', 'commentParents'])->load(['likedUsers', 'updatedUser', 'createdUser']);
        } else {
            return Post::where('category_id', $id_category)->orderBy('created_at',
                'desc')->limit(5)->get()->loadCount(['likedUsers', 'likes', 'commentParents']);
        }
    }
}
