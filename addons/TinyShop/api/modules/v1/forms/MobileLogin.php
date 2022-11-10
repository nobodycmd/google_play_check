<?php

namespace addons\TinyShop\api\modules\v1\forms;

use Yii;
use yii\base\Model;
use common\enums\StatusEnum;
use common\helpers\RegularHelper;
use common\models\member\Member;
use common\models\common\SmsLog;
use addons\TinyShop\common\enums\AccessTokenGroupEnum;

/**
 * Class MobileLogin
 * @package api\modules\v1\models
 * @author Rf <1458015476@qq.com>
 */
class MobileLogin extends Model
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $group;

    /**
     * @var
     */
    protected $_user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile', 'code', 'group'], 'required'],
            ['code', '\common\models\validators\SmsCodeValidator', 'usage' => SmsLog::USAGE_LOGIN],
            ['code', 'filter', 'filter' => 'trim'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            ['mobile', 'validateMobile'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'code' => '验证码',
            'group' => '组别',
        ];
    }

    /**
     * @param $attribute
     */
    public function validateMobile($attribute)
    {
        if (!$this->getUser()) {
            $this->addError($attribute, '找不到用户');
        }
    }

    /**
     * 获取用户信息
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            $this->_user = Member::find()
                ->where(['mobile' => $this->mobile, 'status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->one();
        }

        return $this->_user;
    }
}