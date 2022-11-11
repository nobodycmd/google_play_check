<?php

namespace console\controllers;

use common\models\common\Queue;
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
}