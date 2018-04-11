<?php
/**
 * @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */

namespace www\controllers;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UcenterMember;
use common\models\base\asset\Info;
use frontend\actions\member;
use common\models\base\fund\Product;
use yii\db\Query;
use yii\data\Pagination;
use common\models\base\fund\Income;
use backend\models\Category;
use common\models\base\cms\Cat;
use backend\models\Article;
use common\models\base\cms\Link;
use common\models\base\asset\Log;
use www;
use common\models\base\activity\VirtualProduct;

class InvestmentController extends Controller
{
	public $defaultAction = 'investing';

//邀请注册
    public function actionInvesting()
    {
		//TODO
    	//累计金额
    	$money_total = Log::find()->select("sum(step) as step")->where(['action'=>'Invest/invest','status'=>Log::STATUS_INVEST_SUC])->andWhere(['>', 'create_at', 1441641600])->asArray()->one()['step'];
    	//累计收益
    	$income_total = (new Query())
    		->select("sum(smoney) as smoney")
    		->from("fund_income")
			->where('created_at >= 1441641600 ')
    		->one();
    	//累计人数
    	$people_total = UcenterMember::find()->where('created_at >= 1441641600 ')->count();
    	//项目数量
    	$product_total = Product::find()->where('create_at >= 1441641600 ')->count();
    	//担保机构
    	$cat_id = Cat::find()->where("name='担保机构'")->one()->id;
    	$guarantee = Link::find()->where('cat_id='.$cat_id)->limit(4)->asArray()->all();
    	//投资项目列表
//    	$invest_query = (new Query())
//		->select("*")
//		->from("fund_product")
//		->orderBy('start_at DESC');
		//TODO
		$invest_query = (new Query())
		->select("*")
		->where('create_at >= 1441641600 ')
		->from("fund_product")
		->orderBy('start_at DESC');


    	//分页
    	$pages = new Pagination(['totalCount'=>$invest_query->count(), 'pageSize'=>5]);
    	$invest_datas = $invest_query->offset($pages->offset)
	    	->limit($pages->limit)
	    	->all();
    	//最新投资记录
    	$query = (new Query())
    	->select("C.real_name,A.money,A.start_at")
    	->from("fund_orders AS A")
		->where('A.start_at >= 1441641600 ')
    	->join("left join", "ucenter_member AS C","A.member_id = C.id")
    	->orderBy("start_at DESC");
    	 
    	$pages_new = new Pagination(['totalCount' => $query->count(), 'pageSize' => '8']);
    	$invest_new = $query->offset($pages_new->offset)
    	->limit($pages_new->limit)
    	->all();
    	
    	return $this->render('investing',compact("pages","invest_datas","invest_new","pages_new","income_total","people_total","product_total","guarantee","money_total"));
    }
    
    //最新投资记录
    public function actionInvesting_log()
    {
    	//最新投资记录
//    	$query = (new Query())
//    	->select("C.real_name,A.start_money,A.start_at")
//    	->from("fund_orders AS A")
//    	->join("left join", "ucenter_member AS C","A.member_id = C.id")
//    	->orderBy("start_at DESC");
//		//TODO
////		$query = (new Query())
////			->select("C.real_name,A.start_money,A.start_at")
////			->from("fund_orders AS A")
////			->where('A.start_at >= 1441641600 ')
////			->join("left join", "ucenter_member AS C","A.member_id = C.id")
////			->orderBy("start_at DESC");
//
//    	$pages_new = new Pagination(['totalCount' => $query->count(), 'pageSize' => '4']);
//		$offset = $pages_new->offset;
//		if($pages_new->offset > 3){
//			$offset = $pages_new->offset;
//		}
//    	$invest_new = $query->offset(2)
//    	->limit($pages_new->limit)
//    	->all();
//		//获取最新开售的项目
//		$new_product = Product::find()->where(['status', Product::STATUS_UNLOCK])->andWhere(['<','start_at',time()])->andWhere(['>','end_at',time()])->orderBy('id desc')->one();
//		if($new_product != null){
//			//获取虚拟表中数据
//			$pid = $new_product->id;
//			$invest_people = $new_product->invest_people;
//			$start_at = $new_product->start_at;
//			$VirtualProduct = array();
//			if($invest_people > 0){
//				$VirtualProduct = VirtualProduct::find()->select(['name','money'])->where(['pid' =>$pid])->all();
//			}
//
//		}

		//最新投资记录
		$query = (new Query())
			->select("C.real_name,A.start_money,A.start_at")
			->from("fund_orders AS A")
			->where('A.start_at >= 1441641600 ')
			->join("left join", "ucenter_member AS C","A.member_id = C.id")
			->orderBy("start_at DESC")
			->limit(16)
			->all();
		//获取最新的上线活动--有购买人数
		$time = time();
		$product_query = (new Query())
			->select('f.id')
			->from("fund_product AS f")
			->andWhere("f.start_at < $time")
			->andWhere('f.invest_people > 0')
			->orderBy("start_at DESC")
			->one();
		$pid = $product_query['id'];
		//获取虚拟数据
		$virtual_query = (new Query())
			->select('*')
			->from('virtual_product AS v')
			->where("v.pid <= $pid")
			->orderBy('id desc')
			->limit(16)
			->all();
		$count = 0;
		//拼接新数组
		$new_array = array();
		foreach($query as $value){
			$new_array[$count] = array('real_name' => $value['real_name'],'start_money' => $value['start_money'],'start_at' => $value['start_at']);
			$count ++;
		}
		$new_array2 = array();
		foreach($virtual_query as $value){
			$new_array2[$count] = array('real_name' => $value['name'],'start_money' => $value['money'],'start_at' => $value['create_at']);
			$count++;
		}
		//组合数组
		$arr = array_merge($new_array,$new_array2);
//		var_dump($arr);
//		$count = $query->count() > 16 ? 16 : $query->count();
		//数组排序
		$sort_arr = array();
		foreach($arr as $key=>$value){
			$sort_arr[$key] = $value['start_at'];
		}
		array_multisort($sort_arr,SORT_DESC,$arr);

		$count = count($arr) > 16 ? 16 : count($arr);

		$pages_new = new Pagination(['totalCount' => $count, 'pageSize' => '8']);
//		$invest_new = $query->offset($pages_new->offset)
//			->limit($pages_new->limit)
//			->all();
		$invest_new = array_slice($arr,$pages_new->offset,$pages_new->limit);

    	 
    	return $this->renderPartial('investing_log',compact("invest_new","pages_new","new_product"));
    }
	
	public static function  Ucenter()
	{
		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
		//个人账户
		$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
		 
		$updated_at = $model->updated_at;
		$balance = $model_asset->balance;
		 
		$username = yii::$app->user->identity->username;
		return compact('updated_at','balance','username');
	}
}