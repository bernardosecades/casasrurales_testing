<?php

namespace BernardoSecades\Testing\Code3;

use MyCLabs\Enum\Enum;

/**
 * @method static UserType PREMIUM()
 * @method static UserType REGULAR()
 */
class UserType extends Enum
{
    public const PREMIUM = 'premium';
    public const REGULAR = 'regular';
}
