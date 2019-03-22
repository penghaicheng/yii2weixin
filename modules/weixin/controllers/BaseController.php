<?php
namespace app\modules\weixin\controllers;

use Yii;
use yii\log\FileTarget;

class BaseController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function beforeAction($action) {
        $this->layout = false;
        return true;
    }


    protected function renderJSON($data=[], $msg ="ok", $code = 200){
        header('Content-type: application/json');
        echo json_encode([
            "code" => $code,
            "msg"   =>  $msg,
            "data"  =>  $data,
            "req_id" =>  $this->geneReqId(),
        ]);

        return Yii::$app->end();
    }


    protected function geneReqId(){
        return uniqid();
    }

    protected function renderJSONP($data=[], $msg ="ok", $code = 200) {

        $func = Yii::$app->request->get("jsonp","jsonp_func");

        echo $func."(".json_encode([
                "code" => $code,
                "msg"   =>  $msg,
                "data"  =>  $data,
                "req_id" =>  $this->geneReqId(),
            ]).")";


        return Yii::$app->end();
    }

    public  function post($key, $default = "") {
        return Yii::$app->request->post($key, $default);
    }


    public  function get($key, $default = "") {
        return Yii::$app->request->get($key, $default);
    }

    public function checkSignature(){
    	$signature = trim( $this->get("signature","") );
    	$timestamp = trim( $this->get("timestamp","") );
    	$nonce = trim( $this->get("nonce","") );
		$tmpArr = array( \Yii::$app->params['weixin']['token'], $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
    }

    public   function record_log($msg){
        $log = new FileTarget();
        $log->logFile = Yii::$app->getRuntimePath() . "/logs/weixin_msg_".date("Ymd").".log";
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        $log->messages[] = [
            "[url:{$request_uri}][post:".http_build_query($_POST)."] [msg:{$msg}]",
            1,
            'application',
            microtime(true)
        ];
        $log->export();
    }
}