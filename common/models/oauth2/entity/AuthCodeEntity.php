<?php

namespace common\models\oauth2\entity;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;

/**
 * Class AuthCodeEntity
 * @package common\models\oauth2\entity
 * @author Rf <1458015476@qq.com>
 */
class AuthCodeEntity implements AuthCodeEntityInterface
{
    use EntityTrait, TokenEntityTrait, AuthCodeTrait;
}