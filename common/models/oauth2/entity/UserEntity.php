<?php

namespace common\models\oauth2\entity;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class UserEntity
 * @package common\models\oauth2\entity
 * @author Rf <1458015476@qq.com>
 */
class UserEntity implements UserEntityInterface
{
    use EntityTrait;
}