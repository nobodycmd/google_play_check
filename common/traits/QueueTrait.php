<?php

namespace common\traits;

use Yii;
use yii\helpers\Console;
use yii\helpers\Json;
use common\models\member\Auth;


trait QueueTrait
{
    /**
     * 被控制台类进行引入使用
     * 直接引入后 yii  xx/go queue1
     *
     * 参见类 yii\queue\db\Command ( > yii\queue\cli\Command > yii\console\Controller )
     *
     * @param string $channel_name
     * @return mixed
     */
    public function actionGo($channel_name=''){
        $timeout = 3;
        Console::stdout("队列通道".$channel_name.'开始运行');
        return $this->getQueue($channel_name)->run(true, $timeout);
    }

    /**
     * 根据 通道 获得队列对象
     * @param string $channel_name
     * @return \yii\queue\cli\Queue
     */
    public function getQueue($channel_name = ''){
        $queue = Yii::$app->queue;
        if($channel_name)
            $queue->channel = $channel_name;
        return $queue;
    }

}