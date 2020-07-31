<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use App\Http\Controllers\Controller;
use App\Traits\TransformUserGateway;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class NotificationController extends Controller {

    use TransformUserGateway;
    use CommonResponse;

    protected $_userRepository;
    protected $_postRepository;

    public function __construct(UserRepositoryInterface $userRepository, PostRepositoryInterface $postRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_postRepository = $postRepository;
    }

    public function setting(Request $request) {
        $user = $this->getUser($request);
        $this->_postRepository->find($request->id); // check valid post
        $turnOn = $request->watch == 1 ? 0 : 1;
        $postAttach = ['post_id' => $request->id, 'watch' => $turnOn];
        $user->notificationPost()->sync([$user->user_id => $postAttach]);
        return $this->successResponse(['turnOn' => $turnOn], 'success');
    }
}
