<?php

namespace addons\TinyShop\common\models\forms;

use addons\TinyShop\common\models\order\OrderProduct;

/**
 * Class RefundForm
 * @package addons\TinyShop\common\models\forms
 * @author Rf <1458015476@qq.com>
 */
class RefundForm extends OrderProduct
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['refund_type', 'refund_reason'], 'required', 'on' => 'apply'],
            [['refund_shipping_code', 'refund_shipping_company'], 'required', 'on' => 'salesReturn'],
            [['refund_type'], 'integer'],
            [['refund_evidence'], 'safe'],
            ['refund_require_money', 'number', 'min' => 0],
            [['refund_reason', 'refund_explain', 'refund_shipping_code', 'refund_shipping_company'], 'string', 'max' => 200],
        ];
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => array_keys($this->attributeLabels()),
            'apply' => array_keys($this->attributeLabels()),
            'salesReturn' => array_keys($this->attributeLabels()),
        ];
    }
}