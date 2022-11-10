<?php

namespace addons\TinyShop\services\order;

use common\components\Service;
use addons\TinyShop\common\models\order\ProductMarketingDetail;

/**
 * Class ProductMarketingDetailService
 * @package addons\TinyShop\services\order
 * @author Rf <1458015476@qq.com>
 */
class ProductMarketingDetailService extends Service
{
    /**
     * @param $order_id
     * @param array $data
     */
    public function create($order_id, array $data)
    {
        foreach ($data as $datum) {
            $model = new ProductMarketingDetail();
            $model = $model->loadDefaultValues();
            $model->attributes = $datum;
            $model->order_id = $order_id;
            $model->save();
        }
    }
}