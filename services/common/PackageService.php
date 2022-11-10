<?php

namespace services\common;

use common\models\common\Queue;
use common\models\common\WatchingPackage;
use common\queues\PackageCheckJob;
use common\traits\QueueTrait;
use Yii;
use common\components\Service;


class PackageService extends Service
{

    use QueueTrait;

    public  const STATUS_W = '未入队列';
    public const STATUS_I = '已入队列';

    /**
     * 重置包任务
     *
     * ```php
     *       Yii::$app->services->package->reset()
     * ```
     */
    public function reset()
    {
        $list = WatchingPackage::find()->orderBy('priority desc')->andWhere([
            //'queue_status' => self::STATUS_W,
        ])->all();

        $i = 0;
        /**
         * @var $m WatchingPackage
         */
        foreach ($list as $m){
            if($m->check_datetime && time() - strtotime($m->check_datetime) < 60){
                continue;
            }

            if($jobid = $this->getQueue('q'.($m->id)%3)->priority($m->priority)->push(new PackageCheckJob([
                'package_name' => $m->package_name
            ]))){
                $i ++;
                $m->queue_status = self::STATUS_I;
                $m->jobid = $jobid;
                $m->save();
            }
        }
        return $i;
    }


}