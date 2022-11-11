<?php

namespace console\controllers;

use common\helpers\StringHelper;
use common\queues\PackageSearchJob;
use common\traits\QueueTrait;
use Yii;
use yii\console\Controller;


class TestController extends Controller
{
    public function actionIndex(){
        $pythonfile = Yii::getAlias("@root/web/watchingapp/google_play.py");
        $key = str_replace(' ', '-', 'teenpatti');
        if (StringHelper::isWindowsOS())
            $cmd = " python {$pythonfile} $key";
        else
            $cmd = " python3 {$pythonfile} $key";
        exec($cmd, $output);
        var_dump($output);
    }
}