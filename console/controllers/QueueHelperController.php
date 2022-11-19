<?php

namespace console\controllers;

use common\models\common\Queue;
use common\models\common\WatchingPackage;
use common\queues\PackageSearchJob;
use common\traits\QueueTrait;
use Yii;

require_once Yii::getAlias("@root/vendor/yiisoft/yii2-queue/src/cli/Command.php");
require_once Yii::getAlias("@root/vendor/yiisoft/yii2-queue/src/drivers/db/Command.php");

/**
 * ./yii queue-helper/go q0
 * ./yii queue-helper/go q1
 * ./yii queue-helper/go q2
 *
 * Class QueueHelperController
 * @package console\controllers
 */
class QueueHelperController extends yii\queue\db\Command
{
    use QueueTrait;

    public function actionReset(){
        while (true){
            if(Queue::find()->count() == 0){
                Yii::$app->services->package->reset();
            }
            sleep(600);
        }
    }

    public function actionSearch(){
        $list = WatchingPackage::find()->select('link_name')->distinct()->all();
        /** @var WatchingPackage $one */
        foreach ($list as $one){
            Yii::$app->services->package->getQueue('q0')->push(new PackageSearchJob([
                'key' => $one->link_name,
            ]));
        }
    }
}