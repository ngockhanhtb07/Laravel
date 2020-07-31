<?php

namespace App\Http\Controllers\API;

use App\Enums\CommentType;
use App\Helper\LikeHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Model\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\TransformUserGateway;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;


class CommentController extends Controller
{
    use CommonResponse;
    use TransformUserGateway;
    protected $_commentRepository;
    protected $_postRepository;
    protected $_userRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->_commentRepository = $commentRepository;
        $this->_postRepository = $postRepository;
        $this->_userRepository = $userRepository;
    }


    // function get list comment by post id
    public function listComments($post_id, $user)
    {
        $request = new Request();
        $request->request->set('user_id',$user);
        $userId = $this->getUser($request)->user_id;
        $comments = $this->_commentRepository->findByPost($post_id, $userId);
        return $this->successResponse($comments, 'get data success');
    }

    // function get list comment by parent_id
    public function listReplyComments($parent_id,$user)
    {
        $request = new Request();
        $request->request->set('user_id',$user);
        $userId = $this->getUser($request)->user_id;
        $list_comment = $this->_commentRepository->findByParent($parent_id,$userId);
        if ($list_comment->count()>0)
            return $this->successResponse($list_comment, 'Get data success', 200);
        else
            return $this->successResponse(new \stdClass(),'Data not found', 200);
    }

    // function add new comment
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $userId = $user->user_id;
        $externalId = $user->external_id; // external id for notification create
        $checkValid = $this->checkValidComment($request->post_id, $request->parent_id);
        $type = CommentType::COMMENT;
        if ($checkValid) {
            if (isset($request->parent_id)){
                $type = CommentType::REPLY_COMMENT;
                $request->merge(['comment_parent_id' => $this->getParentId($request->parent_id)]);
                if (isset($request->user_tag) && $request->user_tag != 0){
                    $userTag = $this->_userRepository->getUserByExternalId($request->user_tag) ;
                    $request->merge(['user_tagged' => $userTag->user_id]);
                }
            }
            $request->request->set('user_id',$userId);
            $attribute = $request->only(['content','post_id','user_id','comment_parent_id', 'user_tagged']);
            $comment = $this->_commentRepository->create($attribute);
            $comment->loadCount(['likedUsers','children'])->load(['children','user','userTagged']);
            $comment->setAttribute('owner_id', $userId);
            $data = new CommentResource($comment);
            $responseData = [
                'data' => $data,
                'notify_request' => ''
            ];
            if ($comment) {
                if ($comment->user) {
                    // turn on notification for first time comment
                    $postAttach = ['post_id' => $comment->post_id, 'watch' => 1];
                    $watchNotify = $comment->user->notificationPost->find($comment->post_id);
                    if (!$watchNotify) {
                        $comment->user->notificationPost()->sync($postAttach);
                    }
                }
                $likeHelper = new LikeHelper();
                $notificationRequest = $likeHelper->formatNotification($externalId, $comment->comment_id, $type, false, $userId);
                $responseData['notify_request'] = $notificationRequest;
            }
            return $this->successResponse($responseData, 'create comment success', 200);
        }
        return $this->successResponse(new \stdClass(), 'create comment fail', 400);
    }

    // function check post id, user id, validate post and comment parent valid.
    protected function checkValidComment($post_id, $parent_id)
    {
        $valid = false;
        if (!empty($post_id)) {
            $this->_postRepository->find($post_id);
            if (!is_null($parent_id)) {
                $this->_commentRepository->find($parent_id);
            }
            $valid = true;
        }
        return $valid;
    }

    //function check parent_id of new comment if parent_id is a child comment -> get parent of parent comment
    protected function getParentId($commentId)
    {
        $comment = $this->_commentRepository->findBy('comment_id', $commentId)->first();
        $comment = $comment->comment_parent_id;
        $commentId = $comment ?? $commentId;
        return $commentId;
    }

    // function delete comment by id
    public function delete(Request $request)
    {
        $request->merge(['comment_id' => $request->input('id')]);
        $valid = $this->checkPermissionComment($request);
        $commentId = $request->input('comment_id');
        $responseData = new \stdClass;
        $responseData->data = ['comment_id' => $request->id];
        $type = CommentType::COMMENT;
        // get data to delete notification
        if ($commentId) {
            $likeHelper = new LikeHelper();
            $comment = Comment::findOrFail($commentId);
            if ($comment->parent) {
                $type = CommentType::REPLY_COMMENT;
            }
            $notificationRequest = $likeHelper->formatNotification($comment->user->external_id, $commentId, $type, false, $comment->user->user_id);
            $notificationRequest['is_delete_comment'] = true;
            $responseData->notify_request = $notificationRequest;
        }
        if (!is_null($valid)) {
            $this->_commentRepository->delete($commentId);
            return $this->successResponse($responseData, 'Deleted successfully', 200);
        } else {
            return $this->successResponse(new \stdClass(),'You can not delete this comment', 400);
        }

    }

    // function check self user.
    protected function checkPermissionComment($request)
    {
        $userId = $this->getUser($request)->user_id;
        $request->merge(['user_id' => $userId]);
        $result = $this->_commentRepository->findByListCondition($request->only(['user_id','comment_id']))->first();
        return $result;
    }

    //function edit comment
    public function update(Request $request, $commentId)
    {
        $userId = $this->getUser($request)->user_id;
        // kiem tra neu comment ton tai va dung la user do comment thi duoc phep thay doi
        $comment = $this->_commentRepository->findByPostAndUser($userId, $commentId);
        if (isset($request->user_tag) && !is_null($comment->comment_parent_id)){
            if ($request->user_tag > 0) {
                $userTag = $this->_userRepository->getUserByExternalId($request->user_tag);
                $request->merge(['user_tagged' => $userTag->user_id]);
            } else {
                $request->merge(['user_tagged' => null]);
            }
        }
        $comment = $this->_commentRepository->update($request->only(['content','user_tagged']), $commentId);
        $comment->loadCount(['likedUsers','children'])->load(['children','user','userTagged']);
        $comment->setAttribute('owner_id', $userId);
        $data = new CommentResource($comment);
        return $this->successResponse($data, 'update comment success', 200);
    }
}
