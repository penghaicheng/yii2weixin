<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class MemberController extends Controller
{
    /**
     * 会员列表
     */
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

    /**
     * 会员详情
     */
    public function actionInfo()
    {
        $this->layout=false;
        return $this->render('info');
    }

    /**
     * 会员编辑
     */
    public function actionSet()
    {
        $this->layout=false;
        return $this->render('set');
    }

    /**
     * 会员评论列表
     */
    public function actionComment()
    {
        $this->layout=false;
        return $this->render('comment');
    }
}
