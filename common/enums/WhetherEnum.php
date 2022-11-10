<?php

namespace common\enums;

/**
 * Class WhetherEnum
 * @package common\enums
 * @author Rf <1458015476@qq.com>
 */
class WhetherEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '是',
            self::DISABLED => '否',
        ];
    }
}