<?php

namespace console\controllers;

use yii\console\Controller;
use common\components\TrafficShaper;

/**
 * 令牌桶限流 - 添加器
 *
 * Class TrafficShaperController
 * @package console\controllers
 * @author Rf <1458015476@qq.com>
 */
class TrafficShaperController extends Controller
{
    /**
     * 添加令牌数量
     */
    public function add()
    {
        // 默认最大添加数量为300
        $trafficShaper = new TrafficShaper(300);
        $trafficShaper->add(50);
    }
}