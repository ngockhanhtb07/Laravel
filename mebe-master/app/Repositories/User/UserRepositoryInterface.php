<?php

namespace App\Repositories\User;

use App\Model\User;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface {

    public function setRole($roleName, User $user);

    public function getUserByExternalId($userId);
}