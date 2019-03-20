<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BookController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 图书列表
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 图书编辑
     */
    public function actionSet()
    {
        return $this->render('set');
    }

    /**
     * 图书详情
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * 图书图片
     */
    public function actionImages()
    {
        return $this->render('images');
    }

    /**
     * 图书分类列表
     */
    public function actionCate()
    {
        return $this->render('cate');
    }

    /**
     * 图书分类的编辑
     */
    public function actionCateSet()
    {
        return $this->render('cate_set');
    }
}
