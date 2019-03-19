<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BookController extends Controller
{
    /**
     * 图书列表
     */
    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    /**
     * 图书编辑
     */
    public function actionSet()
    {
        $this->layout = false;
        return $this->render('set');
    }

    /**
     * 图书详情
     */
    public function actionInfo()
    {
        $this->layout = false;
        return $this->render('info');
    }

    /**
     * 图书图片
     */
    public function actionImages()
    {
        $this->layout = false;
        return $this->render('images');
    }

    /**
     * 图书分类列表
     */
    public function actionCate()
    {
        $this->layout = false;
        return $this->render('cate');
    }

    /**
     * 图书分类的编辑
     */
    public function actionCateSet()
    {
        $this->layout = false;
        return $this->render('cate_set');
    }
}
