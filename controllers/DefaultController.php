<?php
/**
 * Created by PhpStorm.
 * User: derek
 * Date: 2019/3/19
 * Time: 15:18
 */

namespace app\controllers;

use app\common\components\BaseWebController;


class DefaultController extends BaseWebController
{
    public function actionIndex()
    {
        $this->layout='main';
        return $this->render('index');
    }
}