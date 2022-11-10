<?php

namespace backend\controllers;

use common\helpers\ExcelHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\helpers\UploadHelper;
use common\models\common\Attachment;
use common\queues\PackageSearchJob;
use Yii;
use common\models\common\WatchingPackage;
use common\traits\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use yii\web\Response;

/**
* WatchingPackage
*https://www.html.cn/softprog/php/45784.html
* Class PackageController
* @package backend\controllers
*/
class PackageController extends BaseController
{
    use Curd;

    /**
    * @var WatchingPackage
    */
    public $modelClass = WatchingPackage::class;

    public function actionUpload()
    {
        try {
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_FILES);
            $upload->verifyFile();
            $upload->save();

            $info = $upload->getBaseInfo();
            $fd = Yii::getAlias("@root/web" . str_replace(Yii::$app->request->getHostInfo(), '', $info['url']));

            $aryData = ExcelHelper::import($fd, 2);

            foreach ($aryData as $v){
//                WatchingPackage::findOne([
//                    ''
//                ])
            }

            return ResultHelper::json(200, '上传成功', $upload->getBaseInfo());
        } catch (\Exception $e) {
            return ResultHelper::json(404, $e->getMessage());
        }
    }

    public function actionReset(){

        $i = Yii::$app->services->package->reset();
        Yii::$app->session->setFlash('success', '已放入队列'.$i.'个包');

        return $this->redirect(['index']);
    }

    public function actionSearch(){
        try {
            $m = new WatchingPackage();
            $m->load(Yii::$app->request->post());

            $pythonfile = Yii::getAlias("@root/web/watchingapp/google_play.py");

            $i = 0;
            $aryKey = explode('$', trim($m->link_name));
            foreach ($aryKey as $key) {

                $key = str_replace(' ', '-', $key);
                if (StringHelper::isWindowsOS())
                    $cmd = " python {$pythonfile} $key";
                else
                    $cmd = " python3 {$pythonfile} $key";

                $jobid = Yii::$app->services->package->getQueue('q0')->push(new PackageSearchJob([
                    'cmd' => $cmd,
                ]));

                if($jobid){
                    $i++;
                }

            }

            Yii::$app->session->setFlash('success', '放入了'.$i.'個關鍵字進行搜索');
        }catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }



    public function actionIndex(){

        if (Yii::$app->request->isAjax && Yii::$app->request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();

            $model = $this->modelClass::findOne($post['editableKey']);

            //$changeModelData[$model->formName()] = $post[$model->formName()][$post['editableIndex']];
            $model->setAttributes($post[$model->formName()][$post['editableIndex']], false);
            $out = ['output' => '', 'message' => ''];

            if ($output = $model->save()) {
                $out = ['output' => $output, 'message' => $model->toArray()];
            } else {
                $out['message'] = $model->getErrors();
            }
            return $out;
        }


        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [
                'package_name','link_name','name',
            ], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

}
