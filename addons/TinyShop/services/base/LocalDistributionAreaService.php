<?php

namespace addons\TinyShop\services\base;

use common\components\Service;
use addons\TinyShop\common\models\base\LocalDistributionArea;

/**
 * Class LocalDistributionAreaService
 * @package addons\TinyShop\services\base
 * @author Rf <1458015476@qq.com>
 */
class LocalDistributionAreaService extends Service
{
    /**
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOne($merchant_id)
    {
        return LocalDistributionArea::find()
            ->where(['merchant_id' => $merchant_id])
            ->asArray()
            ->one();
    }
}