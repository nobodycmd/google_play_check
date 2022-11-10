<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\traits\PayNotify;

/**
 * 支付回调
 *
 * Class NotifyController
 * @package frontend\controllers
 * @author Rf <1458015476@qq.com>
 */
class NotifyController extends Controller
{
    use PayNotify;

    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;
}