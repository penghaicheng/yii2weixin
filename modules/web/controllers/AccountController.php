<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class AccountController extends Controller
{
    /**
     * 账户列表
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    /**
     * 账户编辑或者添加
     */
    public function actionSet()
    {
        $this->layout = false;
        return $this->render('set');
    }

    /**
     * 账户详情
     */
    public function actionInfo()
    {
        $this->layout = false;
        return $this->render('info');
    }
}
