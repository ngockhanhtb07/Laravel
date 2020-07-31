<?php

namespace App\Http\Controllers\API;

use App\Helper\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Diary\DiaryHomeCollection;
use App\Http\Resources\Post\PostShortCollection;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\CommonResponse;
use App\Traits\TransformUserGateway;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    const TYPE_DIARY = 1;
    const TYPE_INFO = 2;
    const TYPE_SHOP = 3;
    use CommonResponse;
    use TransformUserGateway;
    protected $_productRepository;
    protected $_postRepository;
    protected $_userRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->_productRepository = $productRepository;
        $this->_postRepository = $postRepository;
        $this->_userRepository = $userRepository;
    }

    public function search(Request $request, $key)
    {
        $userId = $this->getUser($request)->user_id;
        $type = $request->type;
        $stringHelper = new StringHelper();
        $query = $stringHelper->vnToEng($key);
        $query = strtolower($query);
        $post = $this->_postRepository->searchPost($type, $query, $request->page);
        foreach ($post as $key => $value) {
            if ($value->created_user != $userId && $value->is_enabled == 0) {
                unset($post[$key]);
            } else {
                $value->likes_count = $value->likes->count();
                $value->comment_parents_count = $value->commentParents->count();
                $value->owner_id = $userId;
            }
        }
        if (self::TYPE_DIARY == $type) {
            $data = new DiaryHomeCollection($post);
            return $this->successResponse($data, 'success');
        } else {
            if (self::TYPE_INFO == $type) {
                $data = new PostShortCollection($post);
                return $this->successResponse($data, 'success');
            } else {
                return $this->errorResponse('error search', 400);
            }
        }
    }
}
