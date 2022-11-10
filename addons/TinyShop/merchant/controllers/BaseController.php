<?php

namespace addons\TinyShop\merchant\controllers;

use Yii;
use common\helpers\AddonHelper;
use common\controllers\AddonsController;

/**
 * Class BaseController
 * @package addons\TinyShop\merchant\controllers
 * @author Rf <1458015476@qq.com>
 */
class BaseController extends AddonsController
{
    /**
     * @var string
     */
    public $layout = "@backend/views/layouts/main";

    /**
     * @throws \yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // 注册资源
        AddonHelper::filePath();

        $bundles = Yii::$app->assetManager->bundles;
        foreach ($bundles as $bundle) {
            if (YII_DEBUG && isset($bundle->baseUrl)) {
                $path = Yii::getAlias('@root') . '/web' . $bundle->baseUrl;
                //  FileHelper::removeDirectory($path);
            }
        }

        parent::init();
    }
}