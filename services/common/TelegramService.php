<?php

namespace services\common;

use common\models\common\Queue;
use common\models\common\WatchingPackage;
use common\queues\PackageCheckJob;
use common\traits\QueueTrait;
use linslin\yii2\curl\Curl;
use Yii;
use common\components\Service;


class TelegramService extends Service
{
    public function send($msg,$parse_mode='html')
    {
        $url = "https://api.telegram.org/bot5636105891:AAF6-PvbSadEIP6d1jzBcjvmmiG7J33XWOg/sendMessage";
        $params = [
            "parse_mode" => $parse_mode,
            'chat_id' => '-777847046',
            'text' => $msg,
        ];
        try {
            $curl = new Curl();
            $curl->setGetParams($params);
            $curl->setOptions([
                CURLOPT_TIMEOUT => 3,
                CURLOPT_RETURNTRANSFER => 1,
            ]);
            $res = $curl->get($url);
            if($curl->errorCode){
                return [
                    'code' => $curl->errorCode,
                    'msg' => $curl->errorText,
                ];
            }
            return $res;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    function geturl($url, $params = []){
        try {
            if ($params) {
                $url = $url . '?' . http_build_query($params);
            }
            $headerArray = array("Content-type:application/json;", "Accept:application/json");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            $output = curl_exec($ch);
            curl_close($ch);
            $output = json_decode($output, true);
            return $output;
        }catch (\Exception $e){
            return ['err'=>$e->getMessage()];
        }
    }


}