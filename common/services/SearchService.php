<?php
namespace app\common\services;


use app\models\book\Book;

class SearchService extends BaseService{
	public static function search( $params = [] ){
		$query = Book::find()->where([ 'status' => 1 ]);
		if( isset( $params['kw'] ) ){
			$where_name = [ 'LIKE','name','%'.strtr( $params['kw'] ,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
			$where_tag = [ 'LIKE','tags','%'.strtr( $params['kw'] ,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
			$query->andWhere([ 'OR',$where_name,$where_tag ]);
		}

		if( isset( $params['order_by'] ) ){
			$query->orderBy( $params['order_by'] );
		}else{
			$query->orderBy([ 'id' => SORT_DESC ]);
		}

		$total_count = $query->count();

		if( isset( $params['limit'] ) ){
			$query->limit( $params['limit'] );
		}

		$list = $query->all();
		return [
			'total' => $total_count,
			'data' => $list
		];
	}
}