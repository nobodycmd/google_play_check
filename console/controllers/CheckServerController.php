<?php

namespace console\controllers;

use common\models\common\CheckServer;
use linslin\yii2\curl\Curl;
use yii\console\Controller;


class CheckServerController extends Controller
{
    public function actionIndex(){
        echo 'Go:' . PHP_EOL;
        /** @var $m CheckServer */
        $list = CheckServer::find()->andWhere([
            'enable' => 1,
        ])->all();

        while (true){
            foreach ($list as $m){
                try {
                    $curl = new Curl();
                    $curl->setOptions([
                        CURLOPT_TIMEOUT => 3,
                        CURLOPT_RETURNTRANSFER => 1,
                    ]);
                    $curl->get($m->url);
                    $m->lastchecktime = time();
                    if($curl->errorCode){
                        $m->isokay = 0;
                        $msg = [
                            'code' => $curl->errorCode,
                            'msg' => $curl->errorText,
                            'time' => date('Y-m-d H:i:s'),
                        ];
                        \Yii::$app->services->telegram->send($m->name . " ({$m->url}) : " . json_encode($msg) );
                    }else{
                        $m->isokay = 1;
                    }
                    $m->save();
                }catch (\Exception $e){
                }
            }
            sleep(600);
        }
    }
}