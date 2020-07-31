<?php


namespace App\Repositories\Notification;

use App\Model\NotificationPostUser;
use App\Repositories\EloquentRepository;
use App\Repositories\Media\NotificationRepositoryInterface;

class MediaRepository  extends EloquentRepository implements NotificationRepositoryInterface
{
    public function getModel()
    {
        return NotificationPostUser::class;
    }

}
