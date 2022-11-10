<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "yii_watching_package".
 *
 * @property string $id
 * @property string $package_name
 * @property string $link_name
 * @property string $name
 * @property string $company
 * @property int $is_down
 * @property int $position
 * @property int $had_notify
 * @property int $priority
 * @property int $check_datetime
 * @property int $create_time
 * @property int $live_end_time
 * @property string $star
 * @property string $queue_status
 * @property string $jobid
 * @property string $contact
 * @property string $desc
 * @property string $update_time
 * @property string $download
 */
class WatchingPackage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_watching_package';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_name', 'link_name', 'is_down', 'position', 'had_notify'], 'required'],
            [['is_down', 'position', 'had_notify', 'priority', 'check_datetime', 'create_time', 'live_end_time'], 'integer'],
            [['star'], 'number'],
            [['contact', 'desc'], 'string'],
            [['package_name', 'link_name', 'name', 'company', 'download'], 'string', 'max' => 255],
            [['queue_status', 'jobid', 'update_time'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_name' => 'Package Name',
            'link_name' => 'Link Name',
            'name' => 'Name',
            'company' => 'Company',
            'is_down' => 'Is Down',
            'position' => 'Position',
            'had_notify' => 'Had Notify',
            'priority' => 'Priority',
            'check_datetime' => 'Check Datetime',
            'create_time' => 'Create Time',
            'live_end_time' => 'Live End Time',
            'star' => 'Star',
            'queue_status' => 'Queue Status',
            'jobid' => 'Jobid',
            'contact' => 'Contact',
            'desc' => 'Desc',
            'update_time' => 'Update Time',
            'download' => 'Download',
        ];
    }
}
