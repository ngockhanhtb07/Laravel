<?php


namespace App\Traits;


trait TransformUserGateway
{
    public function getUser($request)
    {
        $userId = $request->get('user_id');
        $user = $this->_userRepository->getUserByExternalId($userId);
        return $user;
    }

    public function getUserByExternalId($externalId) {
        $user = $this->_userRepository->getUserByExternalId($externalId);
        return $user;
    }
}
