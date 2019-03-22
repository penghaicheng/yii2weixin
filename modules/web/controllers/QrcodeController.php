<?php
namespace app\modules\web\controllers;


use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\market\MarketQrcode;
use app\modules\web\controllers\common\BaseController;
use app\common\services\weixin\QrcodeService;

class QrcodeController extends  BaseController{
	public function actionIndex(){
		$mix_kw = trim( $this->get("mix_kw","" ) );
		$p = intval( $this->get("p",1) );
		$p = ( $p > 0 )?$p:1;

		$query = MarketQrcode::find();
		if( $mix_kw ){
			$where_name = [ 'LIKE','name','%'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
			$query->andWhere( $where_name );
		}


		$offset = ($p - 1) * $this->page_size;
		$total_res_count = $query->count();

		$pages = UtilService::ipagination([
			'total_count' => $total_res_count,
			'page_size' => $this->page_size,
			'page' => $p,
			'display' => 10
		]);


		$list = $query->orderBy([ 'id' => SORT_DESC ])
			->offset($offset)
			->limit($this->page_size)
			->all( );

		return $this->render("index",[
			"pages" => $pages,
			'list' => $list,
			'search_conditions' => [
				'mix_kw' => $mix_kw,
				'p' => $p
			]
		]);
	}

	public function actionSet(){
		if( \Yii::$app->request->isGet ){
			$id = intval( $this->get("id",0) );
			$info = [];
			if( $id ){
				$info = MarketQrcode::find()->where([ 'id' => $id ])->one(  );
			}
			return $this->render("set",[
				'info' => $info
			]);
		}

		$id = intval( $this->post("id",0) );
		$name = trim( $this->post("name","") );

		$date_now  = date("Y-m-d H:i:s");

		if( mb_strlen( $name,"utf-8" ) < 1 ){
			return $this->renderJSON( [] , "请输入符合规范的营销渠道名称~~" ,-1);
		}

		$info = MarketQrcode::find()->where([ 'id' => $id ])->one( );
		if( $info ){
			$model_qrcode = $info;
		}else{
			$model_qrcode = new MarketQrcode();
			$model_qrcode->created_time = $date_now;
		}

		$model_qrcode->name = $name;
		$model_qrcode->updated_time = $date_now;
		if( $model_qrcode->save( 0 ) ){
			if( !$model_qrcode->qrcode ){//如果没有二维码，就生成一个
				$ret = QrcodeService::geneTmp( $model_qrcode->id );
				if( $ret ){
					$model_qrcode->extra = @json_encode( $ret );
					$model_qrcode->expired_time = date("Y-m-d H:i:s",time() + $ret['expire_seconds'] );
					$model_qrcode->qrcode = isset( $ret['url'] )?$ret['url']:'';
					$model_qrcode->update( 0 );
				}
			}
		}

		return $this->renderJSON( [],"操作成功~~" );

	}

}