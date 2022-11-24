<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;

class DbinfoForm extends Model
{
    public $ipandport;
    public $dbname;
    public $username;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [[  'username','ipandport','dbname'], 'required'],
            [['password'],'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ipandport' => 'ip和端口',
            'dbname' => '库名',
            'username' => '用户',
            'password' => '密码',
        ];
    }

}
