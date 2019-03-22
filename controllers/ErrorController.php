<?php
/**
 * Created by PhpStorm.
 * User: derek
 * Date: 2019/3/19
 * Time: 15:18
 */

namespace app\controllers;

use app\common\components\BaseWebController;
use app\common\services\applog\ApplogService;
use yii\log\FileTarget;


class ErrorController extends BaseWebController
{
    public function actionError()
    {
        $error = \Yii::$app->getErrorHandler()->exception;

        if ($error) {
            $file = $error->getFile();
            $line = $error->getLine();
            $message = $error->getMessage();
            $code = $error->getCode();

            $log = new FileTarget();
            $log->logFile = \Yii::$app->getRuntimePath() . '/logs/error' . date('ymd', time()) . '.log';

            $err_msg = time() . PHP_EOL;
            $err_msg .= $message . PHP_EOL;
            $err_msg .= 'file:' . $file . PHP_EOL;
            $err_msg .= 'line:' . $line . PHP_EOL;
            $err_msg .= 'code:' . $code . PHP_EOL;
            $err_msg .= 'url:' . $_SERVER['REQUEST_URL'] . PHP_EOL;
            $err_msg .= 'postData:' . http_build_query($_POST) . PHP_EOL;

            $log->messages[] = [
                $err_msg,
                1,
                'application',
                microtime(true),
            ];
            $log->export();

            //todo 写入到数据库
            ApplogService::addErrorLog(\Yii::$app->id, $err_msg);
            //return "错误信息：" . $message;
        }

        $this->layout = false;
        return $this->render('error', ['err_msg' => $message]);
    }
}