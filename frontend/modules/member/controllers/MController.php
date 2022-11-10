<?php
namespace frontend\modules\member\controllers;

use yii\filters\AccessControl;
use frontend\controllers\BaseController;

/**
 * Class MController
 * @package frontend\modules\member\controllers
 * @author Rf <1458015476@qq.com>
 */
class MController extends BaseController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }
}