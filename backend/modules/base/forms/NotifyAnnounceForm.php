<?php

namespace backend\modules\base\forms;

use common\models\backend\Notify;

/**
 * Class NotifyAnnounceForm
 * @package backend\modules\base\forms
 * @author Rf <1458015476@qq.com>
 */
class NotifyAnnounceForm extends Notify
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
        ];
    }
}