<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BrandController extends Controller
{
    /**
     * 品牌详情
     */
    public function actionInfo()
    {
        $this->layout=false;
        return $this->render('info');
    }

    /**
     * 品牌编辑
     */
    public function actionSet()
    {
        $this->layout=false;
        return $this->render('set');
    }

    /**
     * 品牌相册
     */
    public function actionImages()
    {
        $this->layout=false;
        return $this->render('images');
    }
}
