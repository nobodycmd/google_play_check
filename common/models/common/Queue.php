<?php

namespace common\models\common;

use Yii;


class Queue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_queue';
    }


}
