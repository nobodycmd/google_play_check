<?php

namespace addons\TinyShop\common\enums;

use common\enums\BaseEnum;

/**
 * 退换货申请
 *
 * Class RefundTypeEnum
 * @package addons\TinyShop\common\enums
 * @author Rf <1458015476@qq.com>
 */
class RefundTypeEnum extends BaseEnum
{
    const MONEY = 1;
    const MONEY_AND_PRODUCT = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MONEY => '仅退款',
            self::MONEY_AND_PRODUCT => '退款且退货',
        ];
    }
}