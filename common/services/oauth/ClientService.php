<?php
namespace app\common\services\oauth;


use app\models\oauth\OauthToken;
use app\common\services\BaseService;

class ClientService extends  BaseService  {
	/*
	 * 获取登录地址
	 * */
	public static function goLogin( $type ){
		$client_name = __NAMESPACE__."\\".ucfirst($type)."Service";
		$target = new $client_name();
		$url = $target->Login();
		return $url;
	}

	public static function getAccessToken( $type,$params = [] ){
		$client_name = __NAMESPACE__."\\".ucfirst($type)."Service";
		$target = new $client_name();
		$ret = $target->getAccessToken( $params  );
		if( !$ret ){
			return self::_err( $target->getLastErrorMsg() );
		}
		//保存起来access_token
		$access_token = $ret['access_token'];
		$model_oauth_token = new OauthToken();
		$model_oauth_token->client_type = $type;
		$model_oauth_token->token = $access_token;
		$model_oauth_token->valid_to = isset( $ret['expires_in'] )?date("Y-m-d H:i:s",time() + $ret['expires_in'] - 200 ):'0000-00-00 00:00:00';
		$model_oauth_token->note = json_encode( $ret );
		$model_oauth_token->createdt_time = $model_oauth_token->updated_time = date("Y-m-d H:i:s");
		$model_oauth_token->save( 0 );
		return $ret;
	}

	public static function getUserInfo( $type,$access_token = '',$params = [] ){
		
		$client_name = __NAMESPACE__."\\".ucfirst($type)."Service";
		$target = new $client_name();
		$ret = $target->getUserInfo( $access_token,$params  );
		if( !$ret ){
			return self::_err( $target->getLastErrorMsg() );
		}
		return $ret;
	}
}