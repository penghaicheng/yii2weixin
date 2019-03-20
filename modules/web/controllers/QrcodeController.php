<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class QrcodeController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 渠道二维码的列表
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 渠道二维码编辑
     */
    public function actionSet()
    {
        return $this->render('set');
    }
}
