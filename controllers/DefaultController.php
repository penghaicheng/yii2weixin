<?php
/**
 * Created by PhpStorm.
 * User: derek
 * Date: 2019/3/19
 * Time: 15:18
 */

namespace app\controllers;

use yii\web\Controller;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }
}