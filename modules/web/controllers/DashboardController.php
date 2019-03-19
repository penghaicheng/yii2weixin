<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * 仪表盘
 */
class DashboardController extends Controller
{
    /**
     * 仪表盘显示
     */
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }
}
