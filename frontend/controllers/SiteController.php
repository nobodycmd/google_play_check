<?php

namespace frontend\controllers;

use common\helpers\ArrayHelper;
use frontend\forms\DbinfoForm;
use Yii;
use yii\db\Connection;
use yii\web\BadRequestHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\forms\LoginForm;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use frontend\forms\ContactForm;

/**
 * Class SiteController
 * @package frontend\controllers
 * @author Rf <1458015476@qq.com>
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * Success Callback
     * @param yii\authclient\OAuth2|\xj\oauth\WeiboAuth $client
     * @see http://wiki.connect.qq.com/get_user_info
     * @see http://stuff.cebe.cc/yii2docs/yii-authclient-authaction.html
     */
    public function successCallback($client)
    {
        $id = $client->getId(); // qq | sina | weixin
        $attributes = $client->getUserAttributes(); // basic info
        $openid = $client->getOpenid(); //user openid
        $userInfo = $client->getUserInfo(); // user extend info

        Yii::$app->debris->p($id, $attributes, $openid, $userInfo);
        die();
    }

    /**
     * @return Connection
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function getConn(){
        $config = Yii::$app->getSession()->get("conn");
        return Yii::$container->get(\yii\db\Connection::class,[],$config);
    }

    private function addConn($config){
        Yii::$app->getSession()->set($config['dsn'], $config);
        $conns = Yii::$app->getSession()->get("conns");
        if($conns == false){
            $conns = [];
        }
        if(ArrayHelper::isIn($config['dsn'],$conns) == false)
        {
            $conns[] = $config['dsn'];
        }
        Yii::$app->getSession()->set("conns",$conns);
    }

    public function actionGetconn(){
        $config = Yii::$app->getSession()->get($_GET['dsn']);
        if($config)
            $model = $this->configToModel($config);
        else
            $model = new DbinfoForm();
        echo json_encode($model->toArray());
        exit;
    }

    private function configToModel($config){
        $model = new DbinfoForm();
        $model->password = $config['password'];
        $model->username = $config['username'];
        $model->ipandport = explode('=', explode(';',$config['dsn'])[0])[1];
        $model->dbname = explode('=', explode(';',$config['dsn'])[1])[1];
        return $model;
    }

    public function actionClear(){
        Yii::$app->getSession()->removeAll();
        return $this->redirect('/');
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new DbinfoForm();
        $tables = [];
        $resultFields = false;
        $tableName = '';

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $config = [
                "dsn" => "sqlsrv:Server={$model->ipandport};Database={$model->dbname}",
                "username" => $model->username,
                "password" => $model->password,
                'charset' => 'utf8',
            ];
            Yii::$app->getSession()->set("conn", $config);
        }


        if($config = Yii::$app->getSession()->get("conn")) {

            $model = $this->configToModel($config);

            $conn = Yii::$container->get(\yii\db\Connection::class, [], $config);
            try {
                $list = $conn->createCommand("exec sp_tables")->queryAll();
                foreach ($list as $one) {
                    if ($one['TABLE_TYPE'] == 'TABLE') {
                        $tables[] = $one['TABLE_NAME'];
                    }
                }
                $this->addConn($config);
            }catch (\Exception $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        if(isset($_GET['table'])){
            $tableName = $_GET['table'];
$sql = <<<EOF
SELECT  表名 = CASE WHEN a.colorder = 1 THEN d.name
                  ELSE ''
             END ,
        表说明 = CASE WHEN a.colorder = 1 THEN ISNULL(f.value, '')
                   ELSE ''
              END ,
        字段序号 = a.colorder ,
        字段名 = a.name ,
        标识 = CASE WHEN COLUMNPROPERTY(a.id, a.name, 'IsIdentity') = 1 THEN '√'
                  ELSE ''
             END ,
        主键 = CASE WHEN EXISTS ( SELECT  1
                                FROM    sysobjects
                                WHERE   xtype = 'PK'
                                        AND parent_obj = a.id
                                        AND name IN (
                                        SELECT  name
                                        FROM    sysindexes
                                        WHERE   indid IN (
                                                SELECT  indid
                                                FROM    sysindexkeys
                                                WHERE   id = a.id
                                                        AND colid = a.colid ) ) )
                  THEN '√'
                  ELSE ''
             END ,
        类型 = b.name ,
        占用字节数 = a.length ,
        长度 = COLUMNPROPERTY(a.id, a.name, 'PRECISION') ,
        小数位数 = ISNULL(COLUMNPROPERTY(a.id, a.name, 'Scale'), 0) ,
        允许空 = CASE WHEN a.isnullable = 1 THEN '√'
                   ELSE ''
              END ,
        默认值 = ISNULL(e.text, '') ,
        字段说明 = ISNULL(g.[value], '')
FROM    syscolumns a
        LEFT JOIN systypes b ON a.xusertype = b.xusertype
        INNER JOIN sysobjects d ON a.id = d.id
                                   AND d.xtype = 'U'
                                   AND d.name <> 'dtproperties'
        LEFT JOIN syscomments e ON a.cdefault = e.id
        LEFT JOIN sys.extended_properties g ON a.id = G.major_id
                                               AND a.colid = g.minor_id
        LEFT JOIN sys.extended_properties f ON d.id = f.major_id
                                               AND f.minor_id = 0
WHERE   d.name = '$tableName'
ORDER BY a.id ,
        a.colorder
EOF;

$conn = $this->getConn();
$resultFields = $conn->createCommand($sql)->queryAll();
        }

        return $this->render('index', [
            'conns' => array_unique(Yii::$app->getSession()->get("conns")),
            'model' => $model,
            'tables' => $tables,
            'tableName' => $tableName,
            'resultFields' => $resultFields,
        ]);

    }

    /**
     * 登录
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 联系我们
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', '谢谢你联系我们。我们会尽快回复你.');
            } else {
                Yii::$app->session->setFlash('error', '发送你的信息时出错了.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * 关于
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * 注册
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($user = $model->signup()) && Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * 发送重置密码邮件
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '查看您的电子邮件以获得进一步的指示.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', '对不起，我们无法为提供的电子邮件地址重置密码.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * 密码重置
     *
     * @param $token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (UnprocessableEntityHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionOffline()
    {
        return $this->renderPartial('offline', [
            'title' => '系统维护中...'
        ]);
    }
}
