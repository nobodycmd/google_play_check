<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;


class PackageSearchJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var
     */
    public $cmd;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {
        echo $this->cmd . PHP_EOL;

        putenv("PYTHONIOENCODING=utf-8");
        exec($this->cmd, $output);
        @file_put_contents(Yii::getAlias("@root/web/log_search"),json_encode($output));
    }
}