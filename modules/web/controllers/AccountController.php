<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\common\services\UtilService;
use app\models\User;
use app\modules\web\controllers\common\BaseController;

class AccountController extends BaseController
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 账户列表
     * @return string
     */
    public function actionIndex()
    {
        $mix_kw = trim($this->get("mix_kw", ""));
        $status = intval($this->get("status", ConstantMapService::$status_default));
        $p = intval($this->get("p", 1));
        $p = ($p > 0) ? $p : 1;

        $query = User::find();

        if ($mix_kw) {
            //$where_nickname = [ 'LIKE','nickname','%'.$mix_kw.'%', false ];
            //$where_mobile = [ 'LIKE','mobile','%'.$mix_kw.'%', false ];

            $where_nickname = ['LIKE', 'nickname', '%-' . strtr($mix_kw, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '-%', false];
            $where_mobile = ['LIKE', 'mobile', '%-' . strtr($mix_kw, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '-%', false];
            $query->andWhere(['OR', $where_nickname, $where_mobile]);
        }

        if ($status > ConstantMapService::$status_default) {
            $query->andWhere(['status' => $status]);
        }

        //分页功能,需要两个参数，1：符合条件的总记录数量  2：每页展示的数量
        //60,60 ~ 11,10 - 1
        //分页功能已被优化成统一方法 可参考 UtilService::ipagination

        $offset = ($p - 1) * $this->page_size;
        $total_res_count = $query->count();

        $pages = UtilService::ipagination([
            'total_count' => $total_res_count,
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy(['uid' => SORT_DESC])
            ->offset($offset)
            ->limit($this->page_size)
            ->all();

        return $this->render('index', [
            'pages' => $pages,
            'list' => $list,
            'search_conditions' => [
                'mix_kw' => $mix_kw,
                'p' => $p,
                'status' => $status
            ],
            'status_mapping' => ConstantMapService::$status_mapping
        ]);
    }

    /**
     * 账户详情
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * 删除和恢复用户
     * @throws \Throwable
     * @throws \yii\base\ExitException
     * @throws \yii\db\StaleObjectException
     */
    public function actionOps()
    {
        if (!\Yii::$app->request->isPost) {
            return $this->renderJSON([], ConstantMapService::$default_syserror, -1);
        }

        $id = $this->post('id', []);
        $act = trim($this->post('act', ''));
        if (!$id) {
            return $this->renderJSON([], "请选择要操作的账号~~", -1);
        }

        if (!in_array($act, ['remove', 'recover'])) {
            return $this->renderJSON([], "操作有误，请重试~~", -1);
        }

        $info = User::find()->where(['uid' => $id])->one();
        if (!$info) {
            return $this->renderJSON([], "指定账号不存在~~", -1);
        }

        switch ($act) {
            case "remove":
                $info->status = 0;
                break;
            case "recover":
                $info->status = 1;
                break;
        }
        $info->updated_time = date("Y-m-d H:i:s");
        $info->update(0);
        return $this->renderJSON([], "操作成功~~");
    }

    /**
     *  账户编辑或者添加
     * @throws \yii\base\ExitException
     */
    public function actionSet()
    {
        if (\Yii::$app->request->isGet) {
            $id = intval($this->get("id", 0));
            $info = [];
            if ($id) {
                $info = User::find()->where(['uid' => $id])->one();
            }
            return $this->render("set", [
                'info' => $info
            ]);
        }

        $id = intval($this->post("id", 0));
        $nickname = trim($this->post("nickname", ""));
        $mobile = trim($this->post("mobile", ""));
        $email = trim($this->post("email", ""));
        $login_name = trim($this->post("login_name", ""));
        $login_pwd = trim($this->post("login_pwd", ""));
        $date_now = date("Y-m-d H:i:s");

        if (mb_strlen($nickname, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合规范的姓名~~", -1);
        }

        if (mb_strlen($mobile, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合规范的手机号码~~", -1);
        }

        if (mb_strlen($email, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合规范的邮箱地址~~", -1);
        }

        if (mb_strlen($login_name, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合规范的登录名~~", -1);
        }

        if (mb_strlen($login_pwd, "utf-8") < 1) {
            return $this->renderJSON([], "请输入符合规范的登录密码~~", -1);
        }

        if (in_array($login_pwd, ConstantMapService::$low_password)) {
            return $this->renderJSON([], "登录密码太简单，请换一个~~", -1);
        }

        $has_in = User::find()->where(['login_name' => $login_name])->andWhere(['!=', 'uid', $id])->count();
        if ($has_in) {
            return $this->renderJSON([], "该登录名已存在，请换一个试试~~", -1);
        }


        $info = User::find()->where(['uid' => $id])->one();
        if ($info) {
            $model_user = $info;
        } else {
            $model_user = new User();
            $model_user->setSalt();
            $model_user->created_time = $date_now;
        }
        $model_user->nickname = $nickname;
        $model_user->mobile = $mobile;
        $model_user->email = $email;
        $model_user->avatar = ConstantMapService::$default_avatar;
        $model_user->login_name = $login_name;
        if ($login_pwd != ConstantMapService::$default_password) {
            $model_user->setPassword($login_pwd);
        }
        $model_user->updated_time = $date_now;
        $model_user->save(0);

        return $this->renderJSON([], "操作成功~~");

    }
}
