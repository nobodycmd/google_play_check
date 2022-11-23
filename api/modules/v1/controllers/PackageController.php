<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\UploadHelper;
use common\models\common\WatchingPackage;
use linslin\yii2\curl\Curl;
use Symfony\Component\HttpFoundation\UrlHelper;
use yii\db\Exception;
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
    protected $authOptional = ['search', 'save','save-from-nodejs'];


    public function actionSave()
    {
        $ary = ArrayHelper::merge(\Yii::$app->request->get(),\Yii::$app->request->post());
        $m = WatchingPackage::findOne([
            'package_name' => $ary['package_name'],
        ]);
        if(!$m)
        {
            $m = new WatchingPackage();
        }
        $m->setAttributes($ary, false);
        if( $m->isNewRecord ){
            $m->create_time = time();
            $m->queue_status = \Yii::$app->services->package::STATUS_W;
            $m->check_datetime = time();
            $m->is_down = 0;

            $telegramTxt[] = "<b>事件：发现新包上架</b>";
            $telegramTxt[] = '时间：' . date('Y-m-d H:i:s');
            $telegramTxt[] = '新包' . $m->package_name . ' 名称 ' . $m->name;
            $telegramTxt[] = '搜索名称' . $m->link_name;
            $link = "https://play.google.com/store/apps/details?id={$m->package_name}";
            $telegramTxt[] = "<a href='$link'>包详情地址</a>";
            $telegramTxt = implode("%0a", $telegramTxt);
            \Yii::$app->services->telegram->send($telegramTxt);
        }else{
            $m->check_datetime = time();
            if(intval($m->getOldAttribute("is_down")) === 1 && !$m->is_down  ){
                $telegramTxt[] = "<b>事件：发现包重新上架</b>";
                $telegramTxt[] = '时间：' . date('Y-m-d H:i:s');
                $telegramTxt[] = '包' . $m->package_name ;
                $link = "https://play.google.com/store/apps/details?id={$m->package_name}";
                $telegramTxt[] = "<a href='$link'>包详情地址</a>";
                $telegramTxt = implode("%0a", $telegramTxt);
                \Yii::$app->services->telegram->send($telegramTxt);
            }
        }

        if($m->is_down){
            $telegramTxt[] = "<b>google play 包下架通知</b>";
            $telegramTxt[] = '时间：' . date('Y-m-d H:i:s');
            $liveTime = '未知';
            if($m->update_time)
            {
                $telegramTxt[] = "上架时间：" . $m->update_time;
                try{
                    $hr = (time() - strtotime(str_replace("","",$m->update_time))) / 3600;
                    $liveTime = $hr.'小时';
                }catch (\Exception $e){}
            }else{
                $telegramTxt[] = "上架时间：" . $m->update_time;
                try{
                    $hr = (time() - $m->create_time) / 3600;
                    $liveTime = $hr.'小时';
                }catch (\Exception $e){}
            }
            $telegramTxt[] = "存活时间：" . $liveTime;
            $telegramTxt[] = '包' . $m->package_name . ' 名称 ' . $m->name;
            $telegramTxt[] = '搜索名称' . $m->link_name;
            $link = "https://play.google.com/store/apps/details?id={$m->package_name}";
            $telegramTxt[] = "<a href='$link'>包详情地址</a>";

            $telegramTxt = implode("%0a", $telegramTxt);
            \Yii::$app->services->telegram->send($telegramTxt);
            $m->had_notify = 1;
            $m->live_end_time = time();
        }

        if ( $m->save() == false ){
            return $m->errors;
        }
        return isset($notifyUrl) ?  $notifyUrl: 'okay';
    }


    public function actionSaveFromNodejs()
    {
        $ary = ArrayHelper::merge(\Yii::$app->request->get(),\Yii::$app->request->post());

        foreach ($ary as $one) {
            $name = $one['title'];
            $package_name = $one['appId'];

            $m = WatchingPackage::findOne([
                'package_name' => $package_name,
            ]);
            if (!$m) {
                $m = new WatchingPackage();
            }
            $m->setAttributes($one, false);
            $m->name = $name;
            if ($m->isNewRecord) {
                $m->link_name = $m->name;
                $m->create_time = time();
                $m->queue_status = \Yii::$app->services->package::STATUS_W;
                $m->check_datetime = time();
                $m->is_down = 0;

                $telegramTxt[] = "<b>事件：发现新包上架</b>";
                $telegramTxt[] = '时间：' . date('Y-m-d H:i:s');
                $telegramTxt[] = '新包' . $m->package_name . ' 名称 ' . $m->name;
                $telegramTxt[] = '搜索名称' . $m->link_name;
                $link = "https://play.google.com/store/apps/details?id={$m->package_name}";
                $telegramTxt[] = "<a href='$link'>包详情地址</a>";
                $telegramTxt = implode("%0a", $telegramTxt);
                \Yii::$app->services->telegram->send($telegramTxt);
            } else {
                $m->check_datetime = time();
            }

            if ($m->save() == false) {
                throw new Exception(json_encode($m->errors));
            }
        }
        return  'okay';
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
