<?php

namespace console\controllers;

use common\traits\QueueTrait;
use Yii;

require_once Yii::getAlias("@root/vendor/yiisoft/yii2-queue/src/cli/Command.php");
require_once Yii::getAlias("@root/vendor/yiisoft/yii2-queue/src/drivers/db/Command.php");

class QueueHelperController extends yii\queue\db\Command
{
    use QueueTrait;

    public function actionReset(){
        while (1){
            sleep(5);
            Yii::$app->services->package->reset();
        }
    }
}