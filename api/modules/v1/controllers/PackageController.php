<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\helpers\UploadHelper;
use common\models\common\WatchingPackage;
use linslin\yii2\curl\Curl;
use Symfony\Component\HttpFoundation\UrlHelper;
use function Swoole\Coroutine\escape;
use function Swoole\Coroutine\Http\get;


class PackageController extends OnAuthController
{
    public $modelClass = WatchingPackage::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'save'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionSave()
    {
        $ary = \Yii::$app->request->get();
        if(isset($ary['link_name'])){
            $m = WatchingPackage::findOne([
                'package_name' => $ary['package_name'],
                'link_name' => $ary['link_name']
            ]);
        }
        else {
            $m = WatchingPackage::findOne([
                'package_name' => $ary['package_name'],
            ]);
        }
        if(!$m)
        {
            $m = new WatchingPackage();
        }
        $m->setAttributes($ary, false);
        if( $m->isNewRecord ){
            $m->create_time = time();
            $m->queue_status = \Yii::$app->services->package::STATUS_W;
            $m->check_datetime = time();
        }else{
            $m->check_datetime = time();
        }

        if($m->is_down){
            $telegramTxt[] = "<b>google play 包下架通知</b>";
            $telegramTxt[] = '时间：' . date('Y-m-d H:i:s');
            $telegramTxt[] = '包' . $m->package_name . ' 名称 ' . $m->name;
            $telegramTxt[] = '搜索名称' . $m->link_name;
            $link = "https://play.google.com/store/apps/details?id={$m->package_name}";
            $telegramTxt[] = "<a href='$link'>包详情地址</a>";

            $telegramTxt = urlencode(implode(' %0a ', $telegramTxt));

            try{
                $notifyUrl = "https://api.telegram.org/bot5636105891:AAF6-PvbSadEIP6d1jzBcjvmmiG7J33XWOg/sendMessage?parse_mode=html&chat_id=-777847046&text=$telegramTxt";
                (new Curl())->get($notifyUrl);
                $m->had_notify = 1;
            }catch (\Exception $e){}

            $m->live_end_time = time();
        }

        if ( $m->save() == false ){
            return $m->errors;
        }
        return isset($notifyUrl) ?$notifyUrl: 'okay';
    }

    /**
     * 测试查询方法
     *
     * 注意：该方法在 main.php 文件里面的 extraPatterns 单独配置过才正常访问
     *
     * @return string
     */
    public function actionSearch()
    {
        return '测试查询';
    }
}
