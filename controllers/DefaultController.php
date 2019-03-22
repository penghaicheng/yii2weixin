<?php

namespace app\controllers;


use app\common\components\BaseWebController;
use app\common\services\captcha\ValidateCode;
use app\common\services\PayOrderService;
use app\common\services\UtilService;
use app\common\services\weixin\TemplateService;
use app\models\sms\SmsCaptcha;
use app\common\services\AreaService;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;

class DefaultController extends BaseWebController {
	public function actionIndex(){
		return $this->render("index");
	}

	private $captcha_cookie_name = "validate_code";

	public function actionImg_captcha(){
		$font_path = \Yii::$app->getBasePath().'/web/fonts/captcha.ttf';
		$captcha_handle = new ValidateCode( $font_path );
		$captcha_handle->doimg();
		$this->setCookie($this->captcha_cookie_name,$captcha_handle->getCode() );
	}

	public function actionGet_captcha(){
		$mobile = $this->post("mobile");
		$img_captcha = trim( $this->post("img_captcha") );
		$source = trim( $this->post("source","") );
		if ( !$mobile || !preg_match('/^1[0-9]{10}$/', $mobile) ) {
			$this->removeCookie( $this->captcha_cookie_name );
			return $this->renderJSON([],"请输入符合要求的手机号码~~",-1);
		}

		$referer = isset( $_SERVER['HTTP_REFERER'] )?$_SERVER['HTTP_REFERER']:'';

		if( stripos($referer,$_SERVER['HTTP_HOST']) === false ){
			$this->removeCookie( $this->captcha_cookie_name );
			return $this->renderJSON();
		}

		$img_captcha = str_replace(' ', '', $img_captcha);
		$captcha_code = $this->getCookie( $this->captcha_cookie_name,"" );
		if( $captcha_code && ( $captcha_code != strtolower( $img_captcha ) ) ){
			$this->removeCookie( $this->captcha_cookie_name );
			return $this->renderJSON([],"请输入正确图形校验码\r\n您输入的校验码是{$img_captcha},正确的验证码是{$captcha_code}",-1);
		}

		$last = SmsCaptcha::getLastCaptcha($mobile);

		if($last && ( time() - strtotime($last->created_time) < 60 ) ) {
			return $this->renderJSON([],"发送得太快啦",-1);
		}

		$sms_template = "您此次操作的会员绑定验证码为：xxxx";

		$model = new SmsCaptcha();
		$model->geneCustomCaptcha($mobile, $sms_template,UtilService::getIP() );
		$this->removeCookie( $this->captcha_cookie_name );
		if( $model ){
			return $this->renderJSON([],'手机验证码是：'.$model->captcha );
		}
		return $this->renderJSON([],"unknown",-1);
	}

	public function actionQrcode(){
		$qr_code_url = $this->get("qr_code_url","");
		header('Content-type: image/png');
		QrCode::png($qr_code_url,false,Enum::QR_ECLEVEL_H,5,0,false);
		exit();
	}

	public function actionCascade(){
		$province_id = $this->get('id',0);
		$tree_info = AreaService::getProvinceCityTree($province_id);
		return $this->renderJSON( $tree_info );
	}

	public function actionTest(){
	}
}