<?php

namespace addons\RfDevTool\backend\controllers;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use addons\RfDevTool\common\models\MigrateForm;
use jianyan\migration\components\MigrateCreate;
use yii\gii\generators\model\Generator;

/**
 * Class ModelController
 * @package addons\RfDevTool\backend\controllers
 * @author Rf <1458015476@qq.com>
 */
class ModelController extends BaseController
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $model = new MigrateForm();
        // 表列表
        $tableList = array_map('array_change_key_case', Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll());

        // 插件列表
        $addonList = Yii::$app->services->addons->getList();

        $aryMsg = [];

        if ($model->load(Yii::$app->request->post())) {
            if ($model->addon == "0") {
                return $this->message('不要把model自动生成到公用基础model里面去了', $this->redirect(['index']));
            } else {
                $path = Yii::getAlias('@addons') . '/' . $model->addon . '/common/models/';
                FileHelper::mkdirs($path);
            }

            foreach ($model->tables as $table) {
                $g = Yii::createObject(\common\components\gii\model\Generator::class);
                $g->ns = 'addons\\' . $model->addon . '\\common\\models';
                $g->tableName = $table;
                $g->standardizeCapitals = true;

                $files = $g->generate();
                foreach ($files as $file) {
                    try{
                        $file->save();
                    }catch (\Throwable $e){}
                    $aryMsg[] = 'success: ' . $table;
                }
            }

            return $this->message(implode('<br/>', $aryMsg), $this->redirect(['index']));
        }

        return $this->render($this->action->id, [
            'tableList' => ArrayHelper::map($tableList, 'name', 'name'),
            'addonList' => ArrayHelper::merge(['0' => '默认系统（被禁用）'], ArrayHelper::map($addonList, 'name', 'title')),
            'model' => $model
        ]);
    }

}