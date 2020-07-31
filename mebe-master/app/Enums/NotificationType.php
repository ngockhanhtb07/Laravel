<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class NotificationType extends Enum
{
    const NOTIFICATION_TYPE_LIKE = 6;
    const NOTIFICATION_TYPE_LIKE_COMMENT = 7;
    const NOTIFICATION_TYPE_LIKE_REPLY_COMMENT = 8;
    const NOTIFICATION_TYPE_COMMENT = 9;
    const NOTIFICATION_TYPE_REPLY_COMMENT = 10;
}
