<?php

namespace backend\controllers;

use common\models\base\SearchModel;
use common\models\common\Log;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\traits\BaseAction;
use common\helpers\Auth;
use common\behaviors\ActionLogBehavior;
use yii\web\Response;

/**
 * Class BaseController
 * @package backend\controllers
 * @author Rf <1458015476@qq.com>
 */
class BaseController extends Controller
{
    public $enableCsrfValidation = false;

    public $modelClass = Log::class;

    use BaseAction;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // 登录
                    ],
                ],
            ],
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ]
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 每页数量
        $this->pageSize = Yii::$app->request->get('per-page', 10);
        $this->pageSize > 50 && $this->pageSize = 50;

        // 判断当前模块的是否为主模块, 模块+控制器+方法
        $permissionName = '/' . Yii::$app->controller->route;
        // 判断是否忽略校验
        if (in_array($permissionName, Yii::$app->params['noAuthRoute'])) {
            return true;
        }
        // 开始权限校验
        if (!Auth::verify($permissionName)) {
            throw new ForbiddenHttpException('对不起，您现在还没获此操作的权限');
        }

        // 记录上一页跳转
        $this->setReferrer($action->id);


        $this->pageParam = ArrayHelper::merge(Yii::$app->request->get(),Yii::$app->request->post());

        return true;
    }

    /**
     * 页面提交参数
     * get post 一起存储
     *
     * @var array
     */
    protected $pageParam = [];

    /**
     * 获取页面参数
     * @param string $name
     * @param false $default
     * @return array|false|mixed
     */
    public function getPageParam($name='',$default=false){
        if($name == false){
            return $this->pageParam;
        }
        if(isset($this->pageParam[$name])){
            return $this->pageParam[$name];
        }
        return $default;
    }

//    通用修改 搜索  列表页
//    https://www.html.cn/softprog/php/45784.html
//    public function actionIndex(){
//
//        if (Yii::$app->request->isAjax && Yii::$app->request->post('hasEditable')) {
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            $post = Yii::$app->request->post();
//
//            $model = $this->modelClass::findOne($post['editableKey']);
//
//            //$changeModelData[$model->formName()] = $post[$model->formName()][$post['editableIndex']];
//            $model->setAttributes($post[$model->formName()][$post['editableIndex']], false);
//            $out = ['output' => '', 'message' => ''];
//
//            if ($output = $model->save()) {
//                $out = ['output' => $output, 'message' => $model->toArray()];
//            } else {
//                $out['message'] = $model->getErrors();
//            }
//            return $out;
//        }
//
//
//        $searchModel = new SearchModel([
//            'model' => $this->modelClass,
//            'scenario' => 'default',
//            'partialMatchAttributes' => [
//                //'package_name','link_name',
//            ], // 模糊查询
//            'defaultOrder' => [
//                'id' => SORT_DESC
//            ],
//            'pageSize' => $this->pageSize
//        ]);
//
//        $dataProvider = $searchModel
//            ->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//            'searchModel' => $searchModel,
//        ]);
//
//    }

}
