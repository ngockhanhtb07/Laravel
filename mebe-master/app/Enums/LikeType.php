<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LikeType extends Enum
{
    const POST = 1;
    const COMMENT = 2;
    const REPLY_COMMENT = 3;
}
