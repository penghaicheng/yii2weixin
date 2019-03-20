<?php

namespace app\modules\m\controllers;

use yii\web\Controller;

class UserController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * 帐号绑定
     */
    public function actionBind()
    {
        return $this->render('bind');
    }

    /**
     * 我的购物车
     */
    public function actionCart()
    {
        return $this->render('cart');
    }

    /**
     * 我的订单列表页面
     */
    public function actionOrder()
    {
        return $this->render('order');
    }

    /**
     * 我的页面
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 我的地址列表
     */
    public function actionAddress()
    {
        return $this->render('address');
    }

    /**
     * 编辑收货地址
     */
    public function actionAddressSet()
    {
        return $this->render('address_set');
    }

    /**
     * 我的收藏
     */
    public function actionFav()
    {
        return $this->render('fav');
    }
}
