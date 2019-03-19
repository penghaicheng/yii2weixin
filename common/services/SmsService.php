<?php

namespace app\common\services;


use app\common\components\HttpClient;
use app\models\sms\SmsQueue;


class SmsService {
	public static function send( $mobile, $content,$channel = 'default',$ip = '',$sign='企业管家' ) {
		if( !$channel ) {
			$channel = 'default';
		}

		$sms_params= [
			'mobile' =>$mobile,
			'content' => $content,
			'ip' => $ip,
			'channel' => $channel,
			'sign' => $sign
		];
		//加入短信发送队列中去
		self::addSmsQueue(  $sms_params );
		self::Log(sprintf("DO Insert Queue %s\t mobile:%s , content: %s ",date('Y-m-d H:i:s'),$mobile,$content ));
	}


	public static function doSend($mobile, $content,$channel = 'default',$ip='',$sign='') {

		if( !self::recent_history_check($ip) ) {
			return false;
		}

		if( empty($mobile) ) {
			self::Log( "{$mobile} is not mobile,no mobile number,quit.");
			return false;
		}

		$sign = $sign ? $sign : '默认签名';
		$sms_config = \Yii::$app->params['sms'];
		$ret = "success";
		switch( $channel ) {
			case "default"://对接不同的短信供应商平台
			default:
				break;
		}

		return $ret;
	}


	public static function addSmsQueue( $sms_params = []){
		$model_sms_history = new SmsQueue();
		$model_sms_history->mobile = isset( $sms_params['mobile'] )?$sms_params['mobile']:'';
		$model_sms_history->sign = isset( $sms_params['sign'] )?$sms_params['sign']:'';
		$model_sms_history->content = isset( $sms_params['content'] )?$sms_params['content']:'';
		$model_sms_history->channel = isset( $sms_params['channel'] )?$sms_params['channel']:'';
		$model_sms_history->status = isset( $sms_params['status'] )?$sms_params['status']:-2;
		$model_sms_history->ip = isset( $sms_params['ip'] )?$sms_params['ip']:'';
		$model_sms_history->created_time = $model_sms_history->updated_time  = date("Y-m-d H:i:s");
		$model_sms_history->save(0);
	}


	public static function Log($txt){
		$log = \Yii::$app->getRuntimePath().DIRECTORY_SEPARATOR."sms_".date("Y-m-d").".log";
		file_put_contents($log, '[' . date('Y-m-d H:i:s') .']'. $txt."\n",FILE_APPEND);
	}

	/**
	 * ip限制
	 * 1分钟 只能发5次
	 * 2分钟 只能发8次
	 * 3分钟 只能发10次
	 */
	private static function recent_history_check($ip){
		if( $ip ) {

		}
		return true;
	}
}