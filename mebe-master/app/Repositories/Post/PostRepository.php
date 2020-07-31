<?php

namespace App\Repositories\Post;

use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\CMS\PostCollection as PostCMSCollection;
use App\Http\Resources\Post\PostResource;
use App\Model\AttributeValue;
use App\Model\Post;
use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
    const TYPE_DIARY = 1;
    const TYPE_INFO = 2;
    protected $PAGE_DEFAULT = 1;
    protected $ITEM_LIMIT =10;

    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Post::class;
    }

    public function create(array $attributes)
    {
        $data = $this->_model->create($attributes);
        $this->setAttributePost($attributes,$data);
        $data->setAttribute('content_search', $attributes['content_search']);
        $data->addToIndex();
        return $data;

    }

    public function update(array $attributes, $id)
    {
        $result = $this->find($id);
        $result->update($attributes);
        if(isset($attributes['content_search']))
            $result->setAttribute('content_search', $attributes['content_search']);
        $this->setAttributePost($attributes,$result);
        $result->addToIndex();
        return $result;
    }

    private function setAttributePost($attributes, $model){
        if (!empty($attributes['attribute'])){
            $attribute = AttributeValue::where('value', $attributes['attribute'])->firstOrFail();
            $model->attributes()->sync($attribute->attribute_id,true);
            $model->variantAttributes()->sync($attribute->attribute_value_id,true);
        }
        else{
            $this->delAttributePost($model);
        }

    }

    private function delAttributePost($model){
        $model->attributes()->detach();
        $model->variantAttributes()->detach();
    }

    /**
     *
     */
    public function getHottestPost()
    {
        // TODO: Implement getHottestPost() method.
    }

    /**
     * @param $postId
     * @return PostCollection
     */
    public function getRelatedPost($postId)
    {
        $post = $this->_model->findOrFail($postId);
        $relatedPosts = $this->_model->where('category_id', $post->category_id)->where('post_id', '!=',
            $post->post_id)->paginate();
        return new PostCollection($relatedPosts);
    }

    /**
     * @param $userId
     * @param $categoryId
     * @return PostResource|bool
     */
    public function getDiaryPostToday($userId, $categoryId)
    {
        $diaryPost = $this->_model->where('created_user', $userId)
            ->whereDate('created_at', Carbon::today())->where('category_id', $categoryId)
            ->get()->first();
        if (!$diaryPost) {
            return false;
        }
        return new PostResource($diaryPost);
    }

    /**
     * @param $userId
     * @param $categoryId
     * @return PostCollection
     */
    public function getDiaryPost($userId, $categoryId)
    {
        $diaries = $this->_model->where('created_user', $userId)
            ->where('category_id', $categoryId)->orderBy('created_at', 'desc')
            ->paginate();
        return new PostCollection($diaries);
    }

    /**
     * @param $user_id
     * @return \stdClass
     */
    public function countDiaryDate($user_id)
    {
        $diary = $this->_model->where([
            ['created_user', '=', $user_id], ['category_id', '=', 1], ['is_printed', '=', 0]
        ])->count();
        $diaryToday = $this->_model
            ->where('created_user', $user_id)
            ->where('category_id', 1)
            ->whereDate('created_at', Carbon::today())
            ->first();
        $data = new \stdClass();
        $data->day_number = $diary;
        $data->is_writed = is_null($diaryToday) ? 0 : 1;
        $data->today = Carbon::today()->getTimestamp();
        return $data;
    }

    public function getBestDiary($number = 6)
    {
        $date = new \DateTime();
        return $this->_model->where([
            ['category_id', '=', 1],
            ['is_printed', '=', 0],
            ['is_enabled', '=', 1],
            ['status', '=', 1],
        ])->whereDate('created_at', '>=', $date->modify('this week'))
            ->withCount(['likes', 'commentParents'])
            ->with(['likedUsers', 'createdUser', 'medias'])
            ->orderBy('comment_parents_count', 'desc')
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($number);

    }
    
    /**
     * @param  int  $number
     * @return mixed
     */
    public function getNewDiary($number = 6)
    {
        return $this->_model->where([
            ['category_id', '=', 1],
            ['is_printed', '=', 0],
            ['is_enabled', '=', 1],
            ['status', '=', 1],
        ])
            ->with(['likedUsers', 'createdUser','medias'])->withCount(['likes', 'commentParents'])
            ->orderBy('created_at', 'desc')->paginate($number);
    }

    public function getGuide($number = 6)
    {
        return $this->_model->where([
            ['is_enabled', '=', 1],
            ['status', '=', 1],
        ])
            ->whereHas('category', function ($query) {
                $query->where('slug', 'guide-diary');
            })
            ->with(['likedUsers', 'createdUser','medias'])->withCount(['likes', 'commentParents'])
            ->orderBy('created_at', 'desc')->paginate($number);
    }

    public function getMyDiary($user_id, $number = 6, $id = null, $sort = 0)
    {
        $conditions = [
            ['category_id', '=', 1],
            ['status', '=', 1],
            ['created_user', '=', $user_id]
        ];
        if ($id && $id != $user_id) {
            $conditions = [
                ['category_id', '=', 1],
                ['status', '=', 1],
                ['created_user', '=', $id],
                ['is_enabled', '=', 1]
            ];
        }
        if ($sort == 1)
        {
            return $this->_model->where($conditions)
                ->with(['likedUsers', 'createdUser'])->withCount(['likes', 'commentParents'])
                ->paginate($number);
        }
        return $this->_model->where($conditions)
            ->with(['likedUsers', 'createdUser'])->withCount(['likes', 'commentParents'])
            ->orderBy('created_at', 'desc')->paginate($number);
    }

    public function getNews($number = 6) {
        return $this->_model->where([
            ['is_enabled', '=', 1],
            ['status', '=', 1],
        ])
            ->whereHas('category', function ($query) {
                $query->where('slug', 'news');
            })
            ->with(['likedUsers', 'createdUser','medias'])->withCount(['likes', 'commentParents'])
            ->orderBy('created_at', 'desc')->paginate($number);
    }


    public function getFavouritePost($user_id, $type)
    {
        if (self::TYPE_DIARY == $type) {
            $posts = $this->_model->where([
                ['category_id', '=', 1],
                ['status', '=', 1],
            ])
                ->whereHas('favourites', function (Builder $query) use ($user_id) {
                    $query->where('user_id', '=', $user_id);
                })
                ->with(['likedUsers', 'createdUser'])->withCount(['likes', 'commentParents'])
                ->orderBy('created_at', 'desc')->paginate();
        } elseif (self::TYPE_INFO == $type) {
            $posts = $this->_model->where([
                ['category_id', '>', 2],
                ['status', '=', 1],
                ['is_enabled', '=', 1],
            ])
                ->whereHas('favourites', function (Builder $query) use ($user_id) {
                    $query->where('user_id', '=', $user_id);
                })
                ->with(['likedUsers', 'createdUser'])->withCount(['likes', 'commentParents'])
                ->orderBy('created_at', 'desc')->paginate();
        } else {
            $posts = new Collection();
        }
        return $posts;

    }

    public function searchPost( $type, $key, $page)
    {
        $page = isset($request->page) ? $page : $this->PAGE_DEFAULT;
        $offset = ($page -1)* $this->ITEM_LIMIT;

        if (self::TYPE_DIARY == $type) {
            $posts = $this->_model->searchByQuery( $this->_genQueryElasticDiary($key, 1)
                , null, null, $this->ITEM_LIMIT,$offset, null);
            if (substr_count($key, ' ')>0) {
                if ($posts->count() == 0) {
                    $posts = $this->_model->searchByQuery($this->_genQueryElasticDiary($key, 2)
                        , null, null, $this->ITEM_LIMIT, $offset, null);
                }
                if ($posts->count() == 0) {
                    $posts = $this->_model->searchByQuery($this->_genQueryElasticDiary($key, 3)
                        , null, null, $this->ITEM_LIMIT, $offset, null);
                }
            }
            $posts= $posts->paginate();
        } else if (self::TYPE_INFO == $type) {
            $posts = $this->_model->searchByQuery( $this->_genQueryElasticPost($key, 1)
                , null, null, $this->ITEM_LIMIT,$offset, null);
            if (substr_count($key, ' ')>0) {
                if ($posts->count() == 0) {
                    $posts = $this->_model->searchByQuery($this->_genQueryElasticPost($key, 2)
                        , null, null, $this->ITEM_LIMIT, $offset, null);
                }
                if ($posts->count() == 0) {
                    $posts = $this->_model->searchByQuery($this->_genQueryElasticPost($key, 3)
                        , null, null, $this->ITEM_LIMIT, $offset, null);
                }
            }
            $posts = $posts->paginate();
        } else {
            $posts = new Collection();
        }
        return $posts;
    }

    protected function _genQueryElasticDiary($key, $times){
        if (substr_count($key, ' ')>0) {
            if ($times==1) {
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('term' => array('category_id' => '1')),
                            array('match_phrase_prefix' => array('content_search' => '*'.$key.'*'))
                        )
                    )
                );
            }
            else if($times==2){
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('term' => array('category_id' => '1')),
                            array('match' => array('content_search' => array('query'=>$key,'operator'=>'and')))
                        )
                    )
                );
            }
            else{
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('term' => array('category_id' => '1')),
                            array('match' => array('content_search' => array('query'=>$key, 'operator' => 'and','fuzziness'=>1, 'fuzzy_transpositions' => false)))
                        )
                    )
                );
            }
        }
        else{
            return array(
                "bool" => array(
                    'must' => array(
                        array('term' => array('status' => '1')),
                        array('term' => array('category_id' => '1')),
                        array('match_phrase' => array('content_search' => $key))
                    )
                )
            );
        }
    }
    /* get all posts for CMS */
    public function getAllPosts() {
        $posts = $this->_model
            ->where('category_id','>', 2)
            ->orderBy('post_id', 'desc')
            ->paginate();
        return new PostCollection($posts);
    }
    public function  getAllData(){
        return $this->_model->all();
    }
    protected function _genQueryElasticPost($key, $times){
        if (substr_count($key, ' ')>0) {
            if ($times==1) {
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('range' => array('category_id' => array('gt' => 2))),
                            array('term' => array('is_enabled' => '1')),
                            array('match_phrase_prefix' => array('content_search' => '*'.$key.'*'))
                        )
                    )
                );
            }
            else if($times==2){
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('range' => array('category_id' => array('gt' => 2))),
                            array('term' => array('is_enabled' => '1')),
                            array('match' => array('content_search' => array('query'=>$key,'operator'=>'and')))
                        )
                    )
                );
            }
            else{
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('range' => array('category_id' => array('gt' => 2))),
                            array('term' => array('is_enabled' => '1')),
                            array('match' => array('content_search' => array('query'=>$key, 'operator' => 'and','fuzziness'=>1, 'fuzzy_transpositions' => false)))
                        )
                    )
                );
            }
        }
            else
                return array(
                    "bool" => array(
                        'must' => array(
                            array('term' => array('status' => '1')),
                            array('range' => array('category_id' => array('gt' => 2))),
                            array('term' => array('is_enabled' => '1')),
                            array('match_phrase' => array('content_search' => $key))
                        )
                    )
                );

    }

    public function findUpdate(array $data, $attribute)
    {
        if (gettype($attribute)=='array')
        {
            $model = $this->_model->where($attribute)->firstOrFail();
        }
        else{
            $model = $this->_model->findOrFail($attribute);
        }
        $model->update($data);
        if(isset($data['content_search']))
            $model->setAttribute('content_search', $data['content_search']);
        $model->addToIndex();
        return $model;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $this->delAttributePost($result);
            $result->removeFromIndex();
            $result->delete();
            return true;
        }
        return false;
    }

    public function getLatestDiary($userId) {
        $condition = [
            ['created_user', $userId],
            ['status', 1],
            ['category_id', 1]
        ];

        return $this->_model->where($condition)->orderBy('created_at', 'desc')->first();
    }
    public function getNumberDiary($user_id){
        return $this->_model->where([
            ['created_user', '=', $user_id], ['category_id', '=', 1], ['status', 1],['is_enabled', '=', 1]
        ])->count();
    }
}
