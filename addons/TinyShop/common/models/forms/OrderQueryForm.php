<?php

namespace addons\TinyShop\common\models\forms;

use yii\base\Model;

/**
 * Class OrderQueryForm
 * @package addons\TinyShop\common\models\forms
 * @author Rf <1458015476@qq.com>
 */
class OrderQueryForm extends Model
{
    public $synthesize_status = '';
    public $order_type = '';
    public $start_time = '';
    public $end_time = '';
    public $order_sn;
    public $member_id;
    public $keyword;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['order_type', 'order_sn', 'keyword'], 'string'],
            [['member_id', 'start_time', 'end_time', 'synthesize_status'], 'integer'],
        ];
    }
}