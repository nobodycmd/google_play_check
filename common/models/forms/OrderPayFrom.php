<?php

namespace common\models\forms;

use addons\TinyShop\common\models\order\Order;
use common\enums\StatusEnum;
use Yii;
use yii\base\Model;
use common\interfaces\PayHandler;

/**
 * Class OrderPayFrom
 * @package common\models\forms
 * @author Rf <1458015476@qq.com>
 */
class OrderPayFrom extends Model implements PayHandler
{
    /**
     * @var
     */
    public $order_id;

    protected $order;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['order_id', 'required'],
            ['order_id', 'integer', 'min' => 0],
            ['order_id', 'verifyPay'],
        ];
    }

    /**
     * @param $attribute
     */
    public function verifyPay($attribute)
    {
        //此处依赖了tinyshop里面的订单
        $order = Order::findOne($this->$attribute);

        if (!$order) {
            $this->addError($attribute, '找不到订单');

            return;
        }

        if ($order->pay_status == StatusEnum::ENABLED) {
            $this->addError($attribute, '订单已支付，请不要重复支付');

            return;
        }
        $this->order = $order;
    }

    /**
     * 支付说明
     *
     * @return string
     */
    public function getBody(): string
    {
        return '订单支付';
    }

    /**
     * 支付详情
     *
     * @return string
     */
    public function getDetails(): string
    {
        return '';
    }

    /**
     * 支付金额
     *
     * @return float
     */
    public function getTotalFee(): float
    {
        return $this->order['pay_money'];
    }

    /**
     * 获取订单号
     *
     * @return float
     */
    public function getOrderSn(): string
    {
        return $this->order['order_sn'];
    }

    /**
     * 交易流水号
     *
     * @return string
     */
    public function getOutTradeNo()
    {
        return $this->order['out_trade_no'] ?? '';
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->order['merchant_id'];
    }

    /**
     * 是否查询订单号(避免重复生成)
     *
     * @return bool
     */
    public function isQueryOrderSn(): bool
    {
        return true;
    }
}