<?php

namespace app\models\sms;

use Yii;

/**
 * This is the model class for table "sms_captcha".
 *
 * @property integer $id
 * @property string $mobile
 * @property string $captcha
 * @property string $ip
 * @property string $expires_at
 * @property integer $status
 * @property string $created_time
 */
class SmsCaptcha extends \yii\db\ActiveRecord
{
	public static function checkCaptcha($mobile, $captcha)
	{
		$mobile = trim($mobile);
		$captcha = str_replace(' ', '', $captcha);

		$captcha = self::findOne(['mobile' => $mobile, 'captcha' => $captcha]);
		if ($captcha && strtotime($captcha->expires_at) >= time()) {
			$captcha->expires_at = date("Y-m-d H:i:s", time() - 1);
			$captcha->save();
			return true;
		}

		return false;
	}

	public  function geneCustomCaptcha($mobile,$content,$ip='',$sign = '',$channel = ''){
		if( empty($content) ){
			return false;
		}

		if( !$this->allow_send_captcha($mobile,$ip) )
		{
			\app\common\services\SmsService::Log("Custom CAPTCHA not allowed {$mobile} {$ip}");
			return false;
		}
		$this->mobile = $mobile;
		$this->created_time = date("Y-m-d H:i:s",time());
		$this->captcha = (string) rand(10000,99999);
		$this->expires_at = date("Y-m-d H:i:s",time() + 60*10);
		$this->ip = $ip;
		$this->status = 0;
		$content = str_replace("xxxx",$this->captcha,$content);
		\app\common\services\SmsService::send($mobile, $content,$channel,$ip,$sign);

		return $this->save(0);
	}

	public static function getLastCaptcha($mobile) {
		return SmsCaptcha::find()->where(['mobile' => $mobile])
			->orderBy([ 'id'=> SORT_DESC ])->limit(1)->one();
	}

	public function allow_send_captcha($mobile,$ip='')
	{
		$time_limits = [
			1 => 2,
			5 => 3,
			60 => 5,
			1440 => 8
		];//minute => count

		//当天限制
		$captcha_records = $this->find()
			->where(['mobile' => $mobile])
			->andWhere([ '>=','created_time',date("Y-m-d 00:00:00") ])
			->orderBy('id desc')
			->limit(8)
			->all( );

		foreach($time_limits as $minute => $count)
		{
			if(isset($captcha_records[$count-1]) && strtotime($captcha_records[$count-1]['created_time']) > time() - $minute*60)
			{
				return false;
			}
		}

		if($ip)
		{

			$captcha_records = $this->find()
				->where(['ip' => $ip])
				->andWhere([ '>=','created_time',date("Y-m-d 00:00:00") ])
				->orderBy('id desc')
				->limit(8)
				->all( );

			foreach($time_limits as $minute => $count)
			{

				if(isset($captcha_records[$count-1]) && strtotime($captcha_records[$count-1]['created_time']) > time() - $minute*60)
				{
					return false;
				}
			}
		}
		return true;
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_captcha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expires_at', 'created_time'], 'safe'],
            [['status'], 'required'],
            [['status'], 'integer'],
            [['mobile', 'ip'], 'string', 'max' => 20],
            [['captcha'], 'string', 'max' => 10],
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
            'captcha' => 'Captcha',
            'ip' => 'Ip',
            'expires_at' => 'Expires At',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
