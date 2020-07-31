<?php

namespace App\Http\Controllers\API;

use App\Model\Children;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Traits\TransformUserGateway;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Children\ChildrenRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;

class UserController extends Controller
{
    use CommonResponse;
    use TransformUserGateway;
    public $successStatus = 200;

    protected $_cartRepository;
    protected $_customerRepository;
    protected $_userRepository;
    protected $_childrenRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CustomerRepositoryInterface $customerRepository,
        UserRepositoryInterface $userRepository,
        ChildrenRepositoryInterface $childrenRepository
    ) {
        $this->_cartRepository = $cartRepository;
        $this->_customerRepository = $customerRepository;
        $this->_userRepository = $userRepository;
        $this->_childrenRepository = $childrenRepository;
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->merge(['external_id' => $request->input('user_id')]);
        $existingUser = $this->_userRepository->findBy('external_id', $request->input('user_id'));
        if ($existingUser->count() > 0) {
            return $this->errorResponse('This user is exists!', 400);
        }
        $user = $this->_userRepository->create($request->only('external_id', 'avatar', 'display_name', 'phone'));
        if ($user) {
            // set role as user
            $this->_userRepository->setRole('user', $user);
            $customer = $this->createCustomer($user);
            if ($customer && !$customer->cart) {
                // create cart for new user
                $this->createCartByCustomer($customer->customer_id);
            }
        }

        return $this->successResponse($user, 'success');
    }

    public function update(Request $request, $userId)
    {
        $userId = (int)$userId;
        $request->merge(['external_id' => $userId]);
        $user = $this->_userRepository->findBy('external_id', $userId)->first();
        $birthday = $request->get('birthday',0);
        if ($birthday == 0)
            $birthday = '';
        else
            $birthday = $birthday ? Carbon::createFromTimestamp($birthday)->toDateTimeString() : '';
        $request->request->set('birthday', $birthday);
        if (!$user) {
            $user = $this->_userRepository->create($request->only('external_id', 'email', 'avatar', 'display_name',
                'phone', 'type_mom', 'birthday'));
            return $this->successResponse($user, 'success');
        }
        $this->_userRepository->update($request->only('external_id', 'email', 'avatar', 'display_name', 'phone',
            'token_user', 'type_mom', 'birthday'), $user->user_id);

        return $this->successResponse($user, 'success');
    }

    public function details($userId)
    {
        $user = $this->_userRepository->find($userId);
        return $this->successResponse($user, 'success');
    }

    public function createCustomer($user)
    {
        if ($user->getId()) {
            $customerInfo = [
                'user_id' => $user->getId(),
                'type' => 'client',
                'last_name' => $user->display_name,
                'is_active' => true
            ];
            $customer = $this->_customerRepository->create($customerInfo);
            return $customer;
        }
        return false;

    }

    protected function createCartByCustomer($user_id)
    {
        $user = ['customer_id' => $user_id];
        $active = ['status' => env('ACTIVE_DEFAULT_VALUE', true)];
        return $this->_cartRepository->firstOrCreate($user, $active);
    }

    public function updateChildren(Request $request)
    {
        $user = $this->getUserByExternalId($request->user_id);
        if (!$user) {
            return $this->errorResponse('This user is not exists!', 200);
        }
        $children = $this->_childrenRepository->findByListCondition([
            'parent_id' => $user->getId(),
            'external_id' => $request->id
        ]);
        $birthday = new \DateTime(date('Y-m-d', $request->birthday));
        $childrenData = [
            'date_of_birth' => $birthday->format('Y-m-d'),
            'gender' => $request->gender,
            'nickname' => $request->nickname
        ];
        if ($children->count() > 0) {
            $children = $this->_childrenRepository->update($childrenData, $request->id);
        } else {
            $childrenData['external_id'] = $request->id;
            $childrenData['parent_id'] = $user->getId();
            $children = $this->_childrenRepository->create($childrenData);
        }

        return $this->successResponse($children, 'success');
    }
    /**
     * Delete chilren
     */
    public function deleteChildren(Request $request)
    {
        $user = $this->getUserByExternalId($request->user_id);
        if (!$user) {
            return $this->errorResponse('This user is not exists!', 200);
        }
        $children = Children::where('external_id',$request->id_baby)->firstorFail();
        $children->delete();
        return $this->successResponse(new \stdClass(), 'success');
    }
    public function createChild(Request $request)
    {
        $user = $this->getUserByExternalId($request->user_id);
        if (!$user) {
            return $this->errorResponse('This user is not exists!', 200);
        }
        $birthday = new \DateTime(date('Y-m-d', $request->birthday));
        $childData = [
            'date_of_birth' => $birthday->format('Y-m-d'),
            'gender' => $request->gender,
            'nickname' => $request->nickname,
            'external_id' => $request->id,
            'parent_id' => $user->getId()
        ];
        $child = $this->_childrenRepository->create($childData);
        return $this->successResponse($child, 'success');
    }
}
