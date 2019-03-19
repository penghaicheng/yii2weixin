<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * 统计管理
 */
class StatController extends Controller
{
    /**
     * 财务统计
     */
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

    /**
     * 商品售卖统计
     */
    public function actionProduct()
    {
        $this->layout=false;
        return $this->render('product');
    }

    /**
     * 会员消费统计
     */
    public function actionMember()
    {
        $this->layout=false;
        return $this->render('member');
    }

    /**
     * 分享统计
     */
    public function actionShare()
    {
        $this->layout=false;
        return $this->render('share');
    }
}
