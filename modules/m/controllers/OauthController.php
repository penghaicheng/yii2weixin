<?php
namespace app\modules\m\controllers;
use app\common\services\ConstantMapService;
use app\common\services\oauth\ClientService;
use app\common\services\oauth\WeixinService;
use app\common\services\QueueListService;
use app\models\member\Member;
use app\models\oauth\OauthMemberBind;
use app\modules\m\controllers\common\BaseController;
use Yii;
use yii\log\FileTarget;

class OauthController extends BaseController {

    public function actionLogin(){
        /*来这里了就要把openid清除掉*/
        $this->removeWxCookie();

        $scope = $this->get('type', 'snsapi_base');
        $referer = $this->get('referer', '');

        $state = urlencode($referer);
        $target = new WeixinService();
		$url = $target->Login( $scope,$state );
        return $this->redirect($url);
    }

    public function actionCallback(){
        $code = $this->get('code','');
        $state = $this->get('state','');

        if(!$code){
            $this->removeWxCookie();
            $this->record_log("403 weixin auth code param error,code:{$code},state:{$state}");
            return $this->goHome();
        }

        $ret_token = ClientService::getAccessToken( 'weixin',[ 'code' => $code ] );
        if( !$ret_token ){
            $this->removeWxCookie();
            $this->record_log("weixin get userinfo fail:".ClientService::getLastErrorMsg() );
            return $this->goHome();
        }

        $openid  = isset($ret_token['openid'])?$ret_token['openid']:'';
        $unionid  = isset($ret_token['unionid'])?$ret_token['unionid']:'';

        if( !$openid  ){
            $this->removeWxCookie();
            $this->record_log("params uid openid  missed,data:".var_export($ret_token,true) );
            return $this->goHome();
        }

        $this->record_log("auth info:".var_export($ret_token,true) );

		$this->setCookie($this->auth_cookie_current_openid,$openid);
		$this->setCookie($this->auth_cookie_current_unionid,$unionid);

        $reg_bind = OauthMemberBind::findOne([ "openid" => $openid,'type' => ConstantMapService::$client_type_wechat ]);

        if( $reg_bind ){//如果已经绑定了
			$member_info = Member::findOne([ 'id' => $reg_bind['member_id'],'status' => 1]);
			if( !$member_info ){
				$reg_bind->delete();
				return $this->goHome();
			}

			if ( $ret_token['scope'] == "snsapi_userinfo" ){
				$wechat_userinfo = ClientService::getUserInfo( "weixin",$ret_token['access_token'],[ 'uid' => $openid ] );
				if ( $wechat_userinfo ) {
					if( isset( $wechat_userinfo['unionid']) ){
						$reg_bind->unionid = $wechat_userinfo['unionid'];
						$reg_bind->save(0);
					}

					//这个时候做登录特殊处理，例如更新用户名和头像等等新
					if( $member_info->avatar == ConstantMapService::$default_avatar ){
						//需要做一个队列数据库了
						//$wechat_userinfo['headimgurl']
						QueueListService::addQueue( "member_avatar",[
							'member_id' => $member_info['id'],
							'avatar_url' => $wechat_userinfo['headimgurl'],
						] );
					}

					if( $member_info->nickname == $member_info->mobile ){
						$member_info->nickname = $wechat_userinfo['nickname'];
						$member_info->update(0);
					}
				}
			}

			$this->setLoginStatus( $member_info );
		}else{
        	$this->removeAuthToken();
		}

        $reback_url = urldecode($state);
        return $this->redirect($reback_url);
    }


    public function actionLogout(){
        $this->removeAuthToken();
        $this->removeWxCookie();
        $this->goHome();
        return;
    }

    private function removeWxCookie(){
        $this->removeCookie($this->auth_cookie_current_openid);
        $this->removeCookie($this->auth_cookie_current_unionid);
    }

	public  static function record_log($msg){
		$log = new FileTarget();
		$log->logFile = Yii::$app->getRuntimePath() . "/logs/wx_info_".date("Ymd").".log";
		$log->messages[] = [
			"[url:{$_SERVER['REQUEST_URI']}][post:".http_build_query($_POST)."] [msg:{$msg}]",
			1,
			'application',
			microtime(true)
		];
		$log->export();
	}

}
