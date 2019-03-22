<?php

namespace app\commands\queue;




use app\common\services\SmsService;
use app\models\sms\SmsQueue;

class SmsController extends  \app\commands\BaseController {
	/**
	 * 如果跑多个会重复跑如何解决，这个就要求模计算了
	 * php yii queue/sms/run
	 */
	public function actionRun( ){

		$list = SmsQueue::find()->where([ 'status' => -2  ])->orderBy([ 'id' =>SORT_ASC ])->limit( 10 )->all();
		if( !$list ){
			return $this->echoLog( 'no data to handle ~~' );
		}

		foreach( $list as $_sms_info ){
			$this->echoLog("queue_id:{$_sms_info['id']}");
			$_sms_info->status = -1;
			if( $_sms_info->update( 0 ) ){
				$tmp_ret = SmsService::doSend( $_sms_info['mobile'],$_sms_info['content'],$_sms_info['channel'],$_sms_info['ip'],$_sms_info['sign'] );
				$_sms_info->status = $tmp_ret?1:0;
				$_sms_info->return_msg = $tmp_ret;
				$_sms_info->update( 0 );
			}
		}

		return $this->echoLog("it's over ~~");
	}
}