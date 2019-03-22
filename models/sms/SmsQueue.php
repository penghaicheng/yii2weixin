<?php

namespace app\models\sms;

use Yii;

/**
 * This is the model class for table "sms_queue".
 *
 * @property integer $id
 * @property string $mobile
 * @property string $sign
 * @property string $content
 * @property string $channel
 * @property integer $status
 * @property string $return_msg
 * @property string $taskid
 * @property string $ip
 * @property integer $send_number
 * @property string $updated_time
 * @property string $created_time
 */
class SmsQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'send_number'], 'integer'],
            [['updated_time', 'created_time'], 'safe'],
            [['mobile'], 'string', 'max' => 256],
            [['sign'], 'string', 'max' => 10],
            [['content', 'return_msg'], 'string', 'max' => 255],
            [['channel'], 'string', 'max' => 30],
            [['taskid'], 'string', 'max' => 60],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'sign' => 'Sign',
            'content' => 'Content',
            'channel' => 'Channel',
            'status' => 'Status',
            'return_msg' => 'Return Msg',
            'taskid' => 'Taskid',
            'ip' => 'Ip',
            'send_number' => 'Send Number',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
