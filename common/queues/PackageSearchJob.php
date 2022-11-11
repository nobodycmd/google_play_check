<?php

namespace common\queues;

use common\helpers\StringHelper;
use Yii;
use yii\base\BaseObject;


class PackageSearchJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var
     */
    public $key;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {
        try {
            putenv("PYTHONIOENCODING=utf-8");
            $pythonfile = Yii::getAlias("@root/web/watchingapp/google_play.py");


            $key = str_replace(' ', '-', $this->key);
            if (StringHelper::isWindowsOS())
                $cmd = " python {$pythonfile} $key";
            else
                $cmd = " python3 {$pythonfile} $key";

            exec($this->cmd, $output);
            @file_put_contents(Yii::getAlias("@root/web/log_search"), json_encode($output));
        }catch (\Exception $e){}
    }
}