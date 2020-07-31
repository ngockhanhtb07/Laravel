<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helper\StringHelper;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use App\Http\Controllers\Controller;
use App\Traits\TransformUserGateway;
use App\Http\Resources\Post\PostResource;
use App\Repositories\Post\PostRepository;
use App\Http\Resources\Diary\DiaryResource;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Post\PostShortCollection;
use App\Http\Resources\Diary\DiaryHomeCollection;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Http\Resources\Post\CMS\PostResource as PostResourceCMS;
use App\Repositories\FavouritePost\FavouritePostRepositoryInterface;

class PostController extends Controller
{
    use CommonResponse;
    use TransformUserGateway;
    /**
     * @var PostRepository PostRepositoryInterface
     */

    const CATEGORY_DIARY = 1;
    const ENTITY_DIARY = 5;
    const TYPE_IMAGE = 'image';
    const SAVED = 1;
    const REMOVED = 0;
    const TYPE_DIARY = 1;
    const TYPE_INFO = 2;
    protected $_postRepository;
    protected $_favouritePostRepository;
    protected $_userRepository;
    protected $_categoryRepository;
    protected $_mediaRepository;


    public function __construct(
        PostRepositoryInterface $postRepository,
        FavouritePostRepositoryInterface $favouritePostRepository,
        UserRepositoryInterface $userRepository,
        CategoryRepositoryInterface $categoryRepository,
        MediaRepositoryInterface $mediaRepository
    ) {
        $this->_postRepository = $postRepository;
        $this->_favouritePostRepository = $favouritePostRepository;
        $this->_userRepository = $userRepository;
        $this->_categoryRepository = $categoryRepository;
        $this->_mediaRepository = $mediaRepository;
    }

    public function show(Request $request, $category_id)
    {
        if ($request->header('isCMS')) {
            $posts = $this->_postRepository->getAllPosts();
            foreach ($posts as $post) {
                $post->content = '';
            }
            return $this->successResponse($posts, 'Success');
        }
        $userId = $this->getUser($request)->user_id;
        $data = $this->_getPostsByCategory($userId, $category_id);
        if ($data->count() == 0) {
            return $this->successResponse(array([]), 'Data not found', 200);
        } else {
            return $this->successResponse($data, 'success');
        }
    }

    protected function _getPostsByCategory($userId, $category_id)
    {
        $posts = $this->_postRepository
            ->findByListCondition(['category_id' => $category_id])
            ->orderBy('created_at','DESC')
            ->paginate();
        $posts->loadCount(['likes', 'commentParents'])->load(['likedUsers', 'updatedUser', 'createdUser']);
        foreach ($posts as $post) {
            $post->setAttribute('owner_id', $userId);
        }
        $data = new PostShortCollection($posts);
        return $data;
    }

    public function detail(Request $request)
    {
        $userId = $this->getUser($request)->user_id;
        $post = $this->_postRepository->find($request->input('post_id'));
        $post->loadCount(['likes', 'commentParents'])->load([
            'likedUsers',
            'updatedUser',
            'createdUser',
            'variantAttributes'
        ]);
        $post->setAttribute('owner_id', $userId);
        $data = $request->header('isCMS') ? new PostResourceCMS($post) : new PostResource($post);
        return $this->successResponse($data, 'success');
    }

    public function store(Request $request)
    {
        $stringHelper = new StringHelper();
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        $userId = $this->getUser($request)->user_id;
        $categoryId = $request->input('category_id');
        $contentSearch = strtolower($stringHelper->vnToEng($request->content_search));
        if ($request->title) {
            $request->request->add(['slug' => Str::slug($request->title)]);
        }
        $request->request->add(
            ['created_user' => $userId]
        );
        // check info category
        if ($categoryId == self::CATEGORY_DIARY) {
            return $this->successResponse(new \stdClass(), "Can't insert into category $categoryId", 400);
        }
        // format data berfore create
        $request->merge(['content_search' => $contentSearch]);
        if (empty($request->status)) {
            $request->merge(['status' => 1]);
        }
        if (empty($request->is_enabled)) {
            $request->merge(['is_enabled' => 1]);
        }
        $request->merge(['url_image' => $this->setImageUrl($request->input('content'))]);
        $post = $this->_postRepository->create($request->only([
            'created_user',
            'content',
            'title',
            'slug',
            'quote',
            'author',
            'status',
            'is_enabled',
            'url_image',
            'category_id',
            'content_search',
            'status',
            'attribute'
        ]));
        $post->loadCount(['likes', 'commentParents'])->load(['likedUsers', 'updatedUser', 'createdUser']);
        $post->setAttribute('owner_id', $userId);
        $data = new PostResource($post);
        return $this->successResponse($data, 'success');
    }

    private function setImageUrl($content)
    {
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
        return empty($image['src']) ? 'https://media.giphy.com/media/kaMuDepOqFATTF4g3K/giphy.gif' : $image['src'];
    }

    public function createDiary(Request $request)
    {
        $stringHelper = new StringHelper();
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        if (!isset($request->slug) && $request->title) {
            $request->request->add(['slug' => Str::slug($request->title)]);
        }
        $userId = $this->getUser($request)->user_id;
        $request->request->set('created_user', $userId);
        $request->request->set('category_id', self::CATEGORY_DIARY);
        if (is_null($request->input('content'))) {
            $request->merge(['content' => '']);
        }
        $isPrivate = $request->input('is_private');
        if (!$isPrivate) {
            $request->merge(['is_enabled' => 1]);
        } else {
            $request->merge(['is_enabled' => 0]);
        }
        $contentSearch = strip_tags(strtolower($stringHelper->vnToEng($request->content_search)));
        $request->merge(['content_search' => $contentSearch]);
        $post = $this->_postRepository->create($request->only([
            'created_user',
            'is_enabled',
            'content',
            'title',
            'status',
            'category_id',
            'content_search',
        ]))->load('medias');
        $post->setAttribute('owner_id', $userId);
        $data = new DiaryResource($post);
        
        $number_diary = $this->_postRepository->getNumberDiary($userId);
        $this->_userRepository->setDirayNumber($request->user_id,$number_diary);     
        return $this->successResponse($data, 'success');
    }

    public function update(Request $request, $postId)
    {
        if (!isset($request->slug) && $request->title) {
            $request->request->add(['slug' => Str::slug($request->title)]);
        }
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        // check info category
        $categoryId = $request->input('category_id');
        if ($categoryId == self::CATEGORY_DIARY) {
            return $this->successResponse(new \stdClass(), "Can't insert into category $categoryId", 400);
        }
        $userId = $this->getUser($request)->user_id;
        // check info user
        $stringHelper = new StringHelper();
        $contentSearch = strip_tags(strtolower($stringHelper->vnToEng($request->content_search)));
        $request->merge([
            'content_search' => $contentSearch,
            'url_image' => $this->setImageUrl($request->input('content'))
        ]);

        $attribute = $request->only([
            'title',
            'quote',
            'slug',
            'content',
            'url_image',
            'author',
            'category_id',
            'is_enabled',
            'status',
            'content_search',
            'attribute'
        ]);
        $attribute['updated_user'] = $userId;
        $post = $this->_postRepository->update($attribute, $postId);
        $post->loadCount(['likes', 'commentParents'])->load(['likedUsers', 'updatedUser', 'createdUser']);
        $post->setAttribute('owner_id', $userId);
        $data = new PostResource($post);
        return $this->successResponse($data, 'updated successfully');
    }

    public function updateDiary(Request $request, $postId)
    {
        $stringHelper = new StringHelper();
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        // check info user and this post is user's diary.
        $userId = $this->getUser($request)->user_id;
        $condition['post_id'] = $postId;
        $condition['created_user'] = $userId;
        if (isset($request->content_search)) {
            $contentSearch = strtolower($stringHelper->vnToEng($request->content_search));
            $request->merge(['content_search' => $contentSearch]);
        }
        $attribute = $request->only([
            'title',
            'content',
            'is_private',
            'status',
            'content_search'
        ]);
        // check content null
        if ($request->has('content') && $request->input('content') == null) {
            $attribute['content'] = '';
        }
        if (isset($attribute['is_private'])) {
            if ($attribute['is_private'] == 1) {
                $attribute['is_enabled'] = 0;
            } else {
                $attribute['is_enabled'] = 1;
            }
            unset($attribute['is_private']);
        }
        $attribute['updated_user'] = $userId;
        $post = $this->_postRepository->findUpdate($attribute, $condition);
        $post->setAttribute('owner_id', $userId);
        if ($request->new_media != 1) {
            $this->_updateContentDiary($postId, $userId, $request->images);
        }
        $data = new DiaryResource($post);
        if (isset($request->media)) {
            // work on cron job update diary
            $this->_updateImageDiary($postId, $userId, $request->media);
        } else {
            if ($request->new_media == 1) {
                $this->_mediaRepository->findDelete(['owner_id' => $postId], ['entity_id' => self::ENTITY_DIARY]);
            }
        }
        return $this->successResponse($data, 'updated successfully');
    }

    public function delete($postId)
    {
        try {
            $this->_postRepository->delete($postId);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->successResponse('', 'deleted successfully');
    }

    public function deleteDiary(Request $request, $postId)
    {
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);
        // check info user and this post is user's diary.
        $userId = $this->getUser($request)->user_id;
        $this->_postRepository->findByListCondition([
            ['post_id', '=', $postId],
            ['created_user', '=', $userId],
            ['category_id', '=', 1]
        ])->firstOrFail();
        $this->_postRepository->delete($postId);
        $number_diary = $this->_postRepository->getNumberDiary($userId);
        $this->_userRepository->setDirayNumber($request->user_id,$number_diary);
        return $this->successResponse('', 'deleted successfully');
    }

    public function storeFavouritePost(Request $request)
    {
        $userId = $this->getUser($request)->user_id;
        $request->request->add(['user_id' => $userId]);
        // check valid in table favourite post
        $check_valid = $this->_favouritePostRepository->findByListCondition($request->only([
            'post_id',
            'user_id'
        ]))->first();
        $this->_postRepository->find($request->post_id);
        $data = new \stdClass();
        $data->post_id = (int)$request->post_id;
        if (is_null($check_valid)) {
            $this->_favouritePostRepository->create($request->only(['post_id', 'user_id']));
            $data->check_save = self::SAVED;
            return $this->successResponse($data, 'success', 200);
        } else {
            $check_valid->delete();
            $data->check_save = self::REMOVED;
            return $this->successResponse($data, 'success', 200);
        }


    }

    public function countDate($userId)
    {
        $request = new Request();
        $request->merge(['user_id' => $userId]);
        $userId = $this->getUser($request)->user_id;
        $numberDate = $this->_postRepository->countDiaryDate($userId);
        return $this->successResponse($numberDate, 'success', 200);
    }

    public function getBanner($type)
    {
        $numberDate = $this->_mediaRepository->getBanners($type);
        return $this->successResponse($numberDate, 'success', 200);
    }

    public function getDiaries(Request $request, $type)
    {
        $user = $this->getUser($request);
        $sort = $request->get('sort', 0);
        switch ($type) {
            case env('DIARY_HOT', 1):
                $diaries = $this->_postRepository->getBestDiary(15);
                break;
            case env('DIARY_NEW', 2):
                $diaries = $this->_postRepository->getNewDiary(15);
                break;
            case env('DIARY_GUIDE', 3):
                $diaries = $this->_postRepository->getGuide(15);
                break;
            case env('DIARY_SELF', 4):
                $friendId = null;
                if ($request->id) {
                    $friend = $this->getUserByExternalId($request->id);
                    $friendId = $friend->user_id;
                }
                $diaries = $this->_postRepository->getMyDiary($user->user_id, 15, $friendId, $sort);
                break;
            case env('DIARY_NEWS', 5):
                $diaries = $this->_postRepository->getNews(15);
                break;
            default:
                $diaries = new Collection();
        }

        if ($diaries->count() == 0) {
            return $this->successResponse(new \stdClass(), 'Data not found', 200);
        }
        foreach ($diaries as $diary) {
            $diary->setAttribute('owner_id', $user->user_id);
        }
        $data = new DiaryHomeCollection($diaries);
        return $this->successResponse($data, 'success');
    }

    public function listFavourite(Request $request)
    {
        $user = $this->getUser($request);
        $posts = $this->_postRepository
            ->getFavouritePost($user->user_id, $request->type);
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                $post->setAttribute('owner_id', $user->user_id);
            }
            $data = (self::TYPE_DIARY == $request->type) ? new DiaryHomeCollection($posts) : new PostShortCollection($posts);
        } else {
            $data = new \stdClass();
        }
        return $this->successResponse($data, 'success');
    }

    public function getHotNewDiary(Request $request)
    {
        $user = $this->getUser($request);
        $diaryBest = $this->_postRepository->getBestDiary();
        $best = $this->_setItemHome(env('DIARY_HOT'), $diaryBest, "Nhật ký nổi bật nhất", $user->user_id);
        $data = array($best);

        $diaryNew = $this->_postRepository->getNewDiary();
        $new = $this->_setItemHome(env('DIARY_NEW'), $diaryNew, "Nhật ký mới nhất", $user->user_id);

        $guide = $this->_postRepository->getGuide();
        $postGuide = $this->_setItemHome(env('DIARY_GUIDE'), $guide, "Mẹo cho bạn", $user->user_id);

        array_push($data, $new, $postGuide);
        return $this->successResponse($data, 'success');
    }

    protected function _setItemHome($id, $diaries, $title, $userId)
    {
        $count = count($diaries);
        $isCount = ($count > 5) ? 1 : 0;
        for ($i = 0; $i < $count; $i++) {
            if ($i > 4) {
                $diaries->forget($i);
            } else {
                $diaries[$i]->setAttribute('owner_id', $userId);
            }
        }
        $diariesResponse = new DiaryHomeCollection($diaries);
        $diary = $this->_getItemHotNew($id, $title, $diariesResponse, $isCount);
        return $diary;
    }

    public function related($postId)
    {
        $postRelated = $this->_postRepository->getRelatedPost($postId);
        if ($postRelated && $postRelated->count() > 0) {
            return $this->successResponse($postRelated, 'success');
        }
        return $this->errorResponse("Can't find any post related", 200);
    }

    public function getDiary(Request $request)
    {
        $user = $this->getUser($request);
        $condition['post_id'] = $request->diary_id;
        $post = $this->_postRepository->findByListCondition($condition)->firstOrFail();
        if ($post->is_enabled == 0 && $post->created_user != $user->user_id) {
            return $this->successResponse(new \stdClass(), 'success');
        }
        $post->load(['createdUser', 'favouritedUsers', 'likedUsers'])->loadCount(['likes', 'commentParents']);
        $post->setAttribute('owner_id', $user->user_id);
        $data = new DiaryResource($post);
        return $this->successResponse($data, 'success');
    }

    public function checkCompletedDiary(Request $request, $id)
    {
        $user = $this->getUser($request);
        $condition['post_id'] = $id;
        $post = $this->_postRepository->findByListCondition($condition)->firstOrFail();
        $post->setAttribute('owner_id', $user->user_id);
        $data = new DiaryResource($post);
        $response = ['status' => $post->status, 'diary' => $data];
        return $this->successResponse($response, 'success');

    }


    public function getListEntity(Request $request)
    {
        $data = $this->_mediaRepository->getListEntity($request->type);
        return $this->successResponse($data, 'success');
    }

    public function getImages($id)
    {
        $data = $this->_mediaRepository->getImages($id);
        return $this->successResponse($data, 'success');
    }

    public function deleteImage($id)
    {
        $this->_mediaRepository->deleteImage($id);
        $dataResponse = new \stdClass();
        return $this->successResponse($dataResponse, 'success');

    }

    public function updateImage(Request $request, $id)
    {
        $param = $request->all();
        $response = $this->_mediaRepository->update($param, $id);
        return $this->successResponse($response, 'success');
    }

    public function uploadImage(Request $request)
    {
        $userId = $this->getUser($request)->user_id;
        $request->merge(['user_id' => $userId]);
        $request->request->set('owner_id', 0);
        $request->request->set('type', 'image');
        $request_accept = $request->only([
            'link',
            'index',
            'user_id',
            'is_enabled',
            'external_id',
            'owner_id',
            'type',
            'entity_id',
        ]);
        if(isset($request->link_ads)){
            $request_accept = $request->only([
                'link',
                'index',
                'user_id',
                'is_enabled',
                'external_id',
                'owner_id',
                'type',
                'entity_id',
                'link_ads'
            ]);
        }
        $image = $this->_mediaRepository->create($request_accept);
        return $this->successResponse($image, "success", 200);
    }

    /**
     * @param $id
     * @param $title
     * @param $data
     * @param $isCount
     * @return \stdClass
     */
    protected function _getItemHotNew($id, $title, $data, $isCount = 0)
    {
        $object = new \stdClass();
        $object->type = $id;
        $object->title = $title;
        $object->is_load_more = $isCount;
        $object->diaries = $data;
        return $object;
    }

    protected function _updateImageDiary($postId, $userId, $media)
    {
        $dataInsert = [];
        if (count($media) > 0) {
            foreach ($media as $value) {
                $data = [
                    'owner_id' => $postId,
                    'description' => $value['description'],
                    'entity_id' => self::ENTITY_DIARY,
                    'type' => self::TYPE_IMAGE,
                    'link' => $value['link'],
                    'user_id' => $userId,
                    'external_id' => $value['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                array_push($dataInsert, $data);
            }
            $this->_mediaRepository->createManyOrUpdate($dataInsert);
        }
    }

    protected function _updateContentDiary($postId, $userId, $images)
    {
        $mediaArray = json_decode($images);
        $mediaIds = [];
        if ($mediaArray) {
            foreach ($mediaArray as $media) {
                $conditions = [
                    'owner_id' => $postId,
                    'entity_id' => self::ENTITY_DIARY,
                    'user_id' => $userId,
                    'external_id' => isset($media->id) ? $media->id : 0
                ];
                $mediaUpdate = $this->_mediaRepository->findUpdate($conditions, ['description' => $media->content]);
                if ($mediaUpdate && $mediaUpdate->media_id) {
                    $mediaIds[] = $mediaUpdate->media_id;
                }
            }
        }
        $condition = [
            ['owner_id', $postId],
            ['entity_id', 5]
        ];
        $this->_mediaRepository->deleteRemainMedia($condition, $mediaIds);
    }

    /*
     * Get latest detail
     */
    public function latestDetail($id)
    {
        $latestDiary = $this->_postRepository->getLatestDiary($id);
        $result = $this->successResponse($latestDiary, 'success');
        return $result;
    }
}
