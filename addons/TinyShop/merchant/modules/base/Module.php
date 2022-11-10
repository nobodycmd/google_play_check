<?php

namespace addons\TinyShop\merchant\modules\base;

/**
 * Class Module
 * @package addons\TinyShop\merchant\modules\base
 * @author Rf <1458015476@qq.com>
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'addons\TinyShop\merchant\modules\base\controllers';

    public function init()
    {
        parent::init();
    }
}