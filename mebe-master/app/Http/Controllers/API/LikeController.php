<?php

namespace App\Http\Controllers\API;

use App\Enums\LikeType;
use App\Helper\LikeHelper;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use App\Http\Controllers\Controller;
use App\Traits\TransformUserGateway;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;

class LikeController extends Controller
{
    use CommonResponse;
    use TransformUserGateway;

    const IS_LIKED = 1;
    const IS_UNLIKED = 0;
    protected $_likeRepository;
    protected $_postRepository;
    protected $_userRepository;
    protected $_commentRepository;
    protected $_likeHelper;

    public function __construct(
        LikeRepositoryInterface $likeRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->_likeRepository = $likeRepository;
        $this->_postRepository = $postRepository;
        $this->_userRepository = $userRepository;
        $this->_commentRepository = $commentRepository;
        $this->_likeHelper = new LikeHelper();
    }

    public function store(Request $request)
    {
        $id = $request->id; // entity_id
        $type = $request->type;
        $externalId = $request->user_id; // id from gateway
        $user = $this->getUser($request);
        $userId = $user->user_id;
        $request = $this->changeKeyRequest($request);
        $request->request->add(['user_id' => $userId]);
        // check valid comment or post
        $checkValid = $this->checkValid($request);
        if (!$checkValid) {
            return $this->errorResponse('Wrong type comment', 200);
        }
        // check liked comment?
        $checkLike = $this->checkLiked($request);
        $data = new \stdClass;
        if (is_null($checkLike)) {
            // check if in table like not exist record insert to table (like)
            $this->_likeRepository->create($request->only(['user_id','comment_id','post_id','type']));
            $data = $this->setDataLike($id, $type, self::IS_LIKED);
            // dispatch event push notification
            $message = 'like success!';
            $isLike = true;
        } else { // if have record in table remove (unlike)
            $id_like = $checkLike->id;
            $dataResponse = $this->_likeRepository->delete($id_like);
            $message = 'unlike success!';
            if ($dataResponse){
                $data = $this->setDataLike($id, $type, self::IS_UNLIKED);
            }
            $isLike = false;
        }
        if ($data) {
            $data->notify_request = $this->_likeHelper->formatNotification($externalId, $id, $type, $isLike, $userId);
        }
        return $this->successResponse($data, $message, 200);
    }

    protected function changeKeyRequest($request)
    {
        if ($request->type == 1) {
            $request->request->add(['post_id' => $request->id]);
        }
        if ($request->type == 2 || $request->type == 3) {
            $request->request->add(['comment_id' => $request->id]);
        }
        unset($request['id']);
        return $request;
    }

    protected function setDataLike($id, $type, $isLike){
        if ($type == 1){
            $condition['post_id'] = $id;
        } else {
            $condition['comment_id'] = $id;
        }
        $condition['type'] = $type;
        $data = $this->_likeRepository->findByListCondition($condition)->count();
        $response = new \stdClass();
        $response->like_number = $data;
        $response->is_liked = $isLike;
        return $response;
    }

    protected function checkLiked($request){
        $result = null;
        if (!empty($request->post_id)){
            $result = $this->_likeRepository->findByListCondition($request->only(['post_id' , 'user_id']))->first();
        }
        else{
            $result = $this->_likeRepository->findByListCondition($request->only(['comment_id' , 'user_id']))->first();
        }
        return $result;
    }

    protected function checkValid($request)
    {
        $result = null;
        if (!is_null($request->post_id)) {
            $result = $this->_postRepository->find($request->post_id);
        }
        else if (!is_null($request->comment_id))
        {
            $result = $this->_commentRepository->find($request->comment_id);
            if ($request->type == LikeType::REPLY_COMMENT && !$result->parent) {
                return null;
            }
        }
        return $result;
    }

}
