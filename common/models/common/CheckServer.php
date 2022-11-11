<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "yii_check_server".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $lastchecktime
 * @property int $isokay
 * @property int $enable
 */
class CheckServer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_check_server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastchecktime', 'isokay', 'enable'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'lastchecktime' => 'Lastchecktime',
            'isokay' => 'Isokay',
            'enable' => 'Enable',
        ];
    }
}
