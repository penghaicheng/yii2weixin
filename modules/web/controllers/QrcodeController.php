<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class QrcodeController extends Controller
{
    /**
     * 渠道二维码的列表
     */
    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    /**
     * 渠道二维码编辑
     */
    public function actionSet()
    {
        $this->layout = false;
        return $this->render('set');
    }
}
