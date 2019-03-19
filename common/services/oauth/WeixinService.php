<?php
namespace app\common\services\oauth;
use app\common\components\HttpClient;

class WeixinService extends  ClientBase {
	public function Login( $scope = '', $state = '' ){
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize";
		$config_params = \Yii::$app->params;
		$params = [
			'appid' => $config_params['weixin']['appid'],
			'redirect_uri' => $this->getCallback(),
			'response_type' => 'code',
			'scope' => $scope,
			'state' => $state
		];
		return $url."?".http_build_query( $params )."#wechat_redirect";
	}

	public function getAccessToken( $params = [] ){
		$config_params = \Yii::$app->params;
		$get_params = [
			'appid' => $config_params['weixin']['appid'],
			'secret' => $config_params['weixin']['sk'],
			'grant_type' => 'authorization_code',
			'code' => isset( $params['code'] )?$params['code']:''
		];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token";
		$ret = HttpClient::get( $url."?".http_build_query( $get_params ) );
		$ret = @json_decode( $ret,true );
		if( !$ret || isset( $ret['errcode'] ) ){
			return $this->_err( $ret['errmsg'] );
		}
		return $ret;
	}

	public function getUserInfo( $access_token,$params = [] ){
		$url = "https://api.weixin.qq.com/sns/userinfo";
		$get_params = [
			'access_token' => $access_token,
			'openid' => isset( $params['uid'] )?$params['uid']:'',
			'lang' => 'zh_CN'
		];
		$ret = HttpClient::get( $url."?".http_build_query( $get_params ) );
		$ret = @json_decode( $ret,true );
		if( !$ret || isset( $ret['errcode'] ) ){
			return $this->_err( $ret['errmsg'] );
		}
		$ret['avatar'] = $ret['headimgurl'];
		return $ret;
	}

	private  function getCallback(){
		$domain = \Yii::$app->params['domain']['m'];
		$callback = '/oauth/callback';
		return $domain.$callback;
	}
}