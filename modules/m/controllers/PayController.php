<?php

namespace app\modules\m\controllers;

use yii\web\Controller;

class PayController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 支付页面
     */
    public function actionBuy()
    {
        return $this->render('buy');
    }
}
