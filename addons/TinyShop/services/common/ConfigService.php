<?php

namespace addons\TinyShop\services\common;

use common\components\Service;
use addons\TinyShop\common\models\SettingForm;
use common\helpers\AddonHelper;

/**
 * Class ConfigService
 * @package addons\TinyShop\services\common
 * @author Rf <1458015476@qq.com>
 */
class ConfigService extends Service
{
    /**
     * @var SettingForm
     */
    protected $setting;

    /**
     * 配置信息
     *
     * @return SettingForm
     */
    public function setting()
    {
        if (!empty($this->setting)) {
            return $this->setting;
        }

        $setting = new SettingForm();
        $setting->attributes = AddonHelper::getConfig();

        $this->setting = $setting;

        return $this->setting;
    }
}