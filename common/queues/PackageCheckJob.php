<?php

namespace common\queues;

use common\helpers\StringHelper;
use common\models\common\Queue;
use common\models\common\WatchingPackage;
use Yii;
use yii\base\BaseObject;
use yii\log\Logger;


class PackageCheckJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var
     */
    public $package_name;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {
        try {
            $m = WatchingPackage::findOne([
                'package_name' => $this->package_name,
            ]);
            $m->queue_status = Yii::$app->services->package::STATUS_W;
            $m->save();

            $pythonfile = Yii::getAlias("@root/web/watchingapp/google_play.py");
            $key = str_replace(' ', '-', $this->package_name);


            if(StringHelper::isWindowsOS())
                $cmd = " python {$pythonfile} $key check";
            else
                $cmd = " python3 {$pythonfile} $key check";

            putenv("PYTHONIOENCODING=utf-8");
            exec($cmd);
        } catch (\Exception $e) {
            echo $e->getMessage();
            @Yii::getLogger()->log($e->getMessage(),Logger::LEVEL_ERROR);
            @file_put_contents(Yii::getAlias("@root/web/")."/checkpackagejob_err",$e->getMessage());
        }
    }
}