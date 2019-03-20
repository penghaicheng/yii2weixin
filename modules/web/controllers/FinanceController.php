<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class FinanceController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }
    /**
     * 订单列表
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 财务流水
     */
    public function actionAccount()
    {
        return $this->render('account');
    }

    /**
     * 订单详情
     */
    public function actionPayInfo()
    {
        return $this->render('pay_info');
    }
}
