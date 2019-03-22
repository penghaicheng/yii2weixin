<?php

namespace app\modules\web\controllers\common;

use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\models\User;
use app\common\services\applog\ApplogService;

/**
 * web 统一控制器当中会做一些web独有的验证
 * 1，指定特定的布局文件
 * 2，进行登录验证
 * Class BaseController
 * @package app\modules\web\controllers\common
 */
class BaseController extends BaseWebController
{
    protected $page_size = 50;
    public $enableCsrfValidation = false;
    protected $auth_cookie_name = "mooc_book";
    protected $salt = "dm3HsNYz3Uy46Rjg";

    /**
     * 当前登录人信息
     * @var null
     */
    protected $current_user = null;

    public $allowAllAction = [
        'web/user/login',
        'web/user/logout'
    ];

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->layout = "main";
    }

    /**
     * 登录统一验证，每个action前的验证
     * @param $action
     * @return bool
     * @throws \yii\base\ExitException
     */
    public function beforeAction($action)
    {
        $is_login = $this->checkLoginStatus();

        if (in_array($action->getUniqueId(), $this->allowAllAction)) {
            return true;
        }

        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(UrlService::buildWebUrl("/user/login"));
            }
            return false;
        }

        ApplogService::addAppLog($this->current_user['uid']);
        return true;
    }

    /**
     * 验证当前登录状态
     * @return bool
     */
    protected function checkLoginStatus()
    {

        $auth_cookie = $this->getCookie($this->auth_cookie_name);

        if (!$auth_cookie) {
            return false;
        }
        list($auth_token, $uid) = explode("#", $auth_cookie);
        if (!$auth_token || !$uid) {
            return false;
        }
        if ($uid && preg_match("/^\d+$/", $uid)) {
            $user_info = User::findOne(['uid' => $uid, 'status' => 1]);
            if (!$user_info) {
                $this->removeAuthToken();
                return false;
            }
            if ($auth_token != $this->geneAuthToken($user_info)) {
                $this->removeAuthToken();
                return false;
            }
            $this->current_user = $user_info;
            \Yii::$app->view->params['current_user'] = $user_info;
            return true;
        }
        return false;
    }

    public function setLoginStatus($user_info)
    {
        $auth_token = $this->geneAuthToken($user_info);
        $this->setCookie($this->auth_cookie_name, $auth_token . "#" . $user_info['uid']);
    }

    protected function removeAuthToken()
    {
        $this->removeCookie($this->auth_cookie_name);
    }

    /**
     * 生成加密字符串
     * @param $user_info
     * @return string
     */
    public function geneAuthToken($user_info)
    {
        return md5($this->salt . "-{$user_info['login_name']}-{$user_info['login_pwd']}-{$user_info['login_salt']}");
    }

    public function getUid()
    {
        return $this->current_user ? $this->current_user['uid'] : 0;
    }

}