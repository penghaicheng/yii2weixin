<?php
namespace app\commands;


class BaseController extends \yii\console\Controller {
	public function echoLog($msg){
		echo date("Y-m-d H:i:s")." : ".$msg."\r\n";
		return true;
	}

	public function getCur($file_name){
		$file_path = "/data/logs/jobs/cur/{$file_name}";
		$content = '';
		if(file_exists($file_path)){
			$content = file_get_contents($file_path);
		}
		return $content;
	}

	public function setCur($file_name,$content = ''){
		$file_path = "/data/logs/jobs/cur/{$file_name}";
		return file_put_contents($file_path,$content);
	}
}