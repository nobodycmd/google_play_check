<?php

namespace addons\TinyShop\merchant\assets;

use yii\web\AssetBundle;

/**
 * 静态资源管理
 *
 * Class AppAsset
 * @package addons\TinyShop\merchant\assets
 * @author Rf <1458015476@qq.com>
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@addons/TinyShop/merchant/resources/';

    public $css = [
        'css/tinyshop.css',
    ];

    public $js = [
        'js/tinyshop.js',
    ];

    public $depends = [
    ];
}