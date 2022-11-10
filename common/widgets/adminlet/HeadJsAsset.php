<?php

namespace common\widgets\adminlet;

use yii\web\AssetBundle;

/**
 * Class HeadJsAsset
 * @package backend\assets
 * @author Rf <1458015476@qq.com>
 */
class HeadJsAsset extends AssetBundle
{
    public $js = [
        'bower_components/jquery/dist/jquery.min.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources';
        parent::init();
    }
}