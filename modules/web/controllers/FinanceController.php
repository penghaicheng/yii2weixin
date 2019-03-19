<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class FinanceController extends Controller
{
    /**
     * 订单列表
     */
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

    /**
     * 财务流水
     */
    public function actionAccount()
    {
        $this->layout=false;
        return $this->render('account');
    }

    /**
     * 订单详情
     */
    public function actionPayInfo()
    {
        $this->layout=false;
        return $this->render('pay_info');
    }
}
