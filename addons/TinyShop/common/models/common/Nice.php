<?php

namespace addons\TinyShop\common\models\common;

/**
 * Class Nice
 * @package addons\TinyShop\common\models\common
 * @author Rf <1458015476@qq.com>
 */
class Nice extends Follow
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_shop_common_nice}}';
    }
}