<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BrandController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 品牌详情
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * 品牌编辑
     */
    public function actionSet()
    {
        return $this->render('set');
    }

    /**
     * 品牌相册
     */
    public function actionImages()
    {
        return $this->render('images');
    }
}
