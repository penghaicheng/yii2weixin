<?php

namespace app\common\services\member;


use app\common\services\BaseService;
use app\common\services\ConstantMapService;
use app\common\services\UtilService;
use app\models\member\Member;

class MemberService extends BaseService {
	public static function set( $params = [] ){
		$mobile = isset( $params['mobile'] )?$params['mobile']:'';

		if( Member::findOne([ 'mobile' => $mobile]) ){
			return self::_err("手机号码已注册，请直接使用手机号码登录~~");
		}

		$model = new Member();
		$model->nickname = $mobile;
		$model->mobile = $mobile;
		$model->setSalt();
		$model->avatar = ConstantMapService::$default_avatar;
		$model->reg_ip = sprintf("%u",ip2long(UtilService::getIP()));
		$model->status = 1;
		$model->created_time = $model->updated_time = date("Y-m-d H:i:s");
		if( !$model->save(0) ){
			return self::_err( '系统繁忙请稍后再试~~' );
		}
		return $model->id;
	}

	public static function OauthBind(){

	}

	public static function getRandomName( $login_name ){
		$has_in = Member::findOne([ 'nickname'=> $login_name ]);
		if ($has_in) {
			$login_name .= '_'.rand(1000,9999);
			return self::getRandomName($login_name);
		}
		return $login_name;
	}
}