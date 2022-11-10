<?php

namespace addons\Merchants\backend\controllers;

use Yii;

/**
 * Class StatController
 * @package addons\Merchants\backend\controllers
 * @author Rf <1458015476@qq.com>
 */
class StatController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->action->id, [
            'merchantCount' => Yii::$app->services->merchant->getCount(),
            'merchantAccount' => Yii::$app->services->merchantAccount->getSum(),
        ]);
    }
}