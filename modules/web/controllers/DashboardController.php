<?php

namespace app\modules\web\controllers;

use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

/**
 * 仪表盘
 */
class DashboardController extends BaseController
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 仪表盘显示
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
