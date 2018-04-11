<?php
/**
 * @2015年7月25日 09:11:57
 * @刘少华
 * @万虎网络
 */
namespace www\controllers;

use yii\web\Controller;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UcenterMember;
use common\models\base\asset\Info;
use common\models\base\fund\Income;
use common\models\fund\Order;
use common\models\base\asset\Log;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Response;
use common\models\base\experience\Gold;
use yii\base\ErrorException;

class AccountController extends Controller
{
	public $defaultAction = 'overview';
	
	public function behaviors()
	{
		return [
			'access' => [
					'class' => \yii\filters\AccessControl::className(),
					'rules' => [
						['allow' =>true,'roles' => ['@'],],
					],
					],
				];
	}
	//账户概览
  	public function actionOverview()
  	{
  		$uid = yii::$app->user->id;
  		
  		$model = UcenterMember::find()->where(['id'=>$uid])->one();
  		$infos_rar = self::datas();
  		return $this->render('overview', compact("model", "infos_rar"));
  	}
  	
  	//投资记录
  	public function actionInvestlog()
  	{
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		$infos_rar = self::datas();
  		return $this->render('investlog', compact("model_asset","model","model_order", "infos_rar","array_investlog","array_tradelog","pages","pages_trade","invest_log"));
  	}
  	
  	//交易记录
  	public function actionTradelog()
  	{
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		$infos_rar = self::datas();
  		return $this->render('tradelog', compact("model_asset","model","model_order", "infos_rar","array_investlog","array_tradelog","pages","pages_trade","invest_log"));
  	}
  	
  	//充值记录
  	public function actionChargerecord()
  	{
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		$query = Log::find()->where(['member_id'=>Yii::$app->user->id,'status'=>1]);
  		$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
  		$recharge_log = $query->offset($pages->offset)
  		->limit($pages->limit)
  		->orderBy("id DESC")
  		->all();
  		$pages_recharge = $pages->offset;
  	
  		$infos_rar = self::datas();	//用户数据包
  		return $this->render('chargerecord', compact("infos_rar","recharge_log","pages_recharge","pages","model"));
  	}
  	 
  	//提现记录
  	public function actionWithdrawrecord()
  	{
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		$query = Log::find()->where(['member_id'=>Yii::$app->user->id,'status'=>4]);
  		$pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
  		$withdraw_log = $query->offset($pages->offset)
  		->limit($pages->limit)
  		->orderBy("id DESC")
  		->all();
  		$pages_withdraw = $pages->offset;
  		$infos_rar = self::datas();	//用户数据包
  		return $this->render('withdrawrecord', compact("infos_rar","withdraw_log","pages_withdraw","pages","model"));
  	}
  	
  	//公共数据包
  	private static function datas()
  	{
  		$uid = yii::$app->user->id;
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		//个人账户
  		$model_asset = Info::find()->where(['member_id'=>Yii::$app->user->id])->one();
  		if(!isset($model_asset))
  		{
  			//跳转至绑定银行卡
  			echo "<script>alert('您还没有绑定银行卡');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['money/bindcard'])."'</script>";
  			exit;
  		}
  		//收益总额
  		$income_total = $model_asset->total_revenue;
  		//当前收益
		$income_current = $model_asset->profit;
  		//昨日收益
		try 
		{
  			$income_yesterday = Income::find()->where(['member_id'=>yii::$app->user->id,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->smoney;
		}
		catch (ErrorException $e)
		{

			$income_yesterday = 0;
		}
//  		 体验金
  		$experience_money = 0;
        $money = Gold::find()->where(['<','created_at',time()])->andWhere(['>','end_at',time()])->andWhere(['uid' => $uid ,'status' => Gold::STATUS_ACTIVE ])->sum('money');
        if($money){
            $experience_money = sprintf("%.2f", $money);
        }
  		
  		//投资总额
  		$invest_total = 0;
  		$model_order = Order::find()->where(['member_id'=>Yii::$app->user->id,'status'=>1])->all();
  		if(count($model_order) > 0)
  		{
  			foreach ($model_order as $K => $V)
  			{
  				$invest_total  += $V->money;
  			}
  		}
  		
  		//用户投资记录
  		$query1 = (new Query())
  		->select("A.step,A.create_at,B.title")
  		->from("asset_log AS A")
  		->where('A.member_id='.Yii::$app->user->id." AND A.action='Invest/invest' AND A.status=2 ")
  		->join("left join", "fund_product AS B","A.product_id=B.id")
  		->orderBy("A.id DESC");
  		$pages = new Pagination(['totalCount' => $query1->count(), 'pageSize' => '10']);
  		$array_investlog = $query1->offset($pages->offset)
  		->limit($pages->limit)
  		->all();
  		$pages_offset = $pages->offset;
  		//用户交易记录
  		$query2 = Log::find()->where(['member_id'=>Yii::$app->user->id,'status'=>[1,2,3,4]]);
  		$pages_trade = new Pagination(['totalCount' => $query2->count(), 'pageSize' => '2']);
  		$array_tradelog = $query2->offset($pages_trade->offset)
  		->limit($pages_trade->limit)
  		->orderBy("id DESC")
  		->all();
  		$pages_trade_offset = $pages_trade->offset;
  		
  		//当前投资记录
  		$query3 = (new Query())
  		->select("A.step,A.create_at,B.title")
  		->from("asset_log AS A")
  		->where('A.member_id='.Yii::$app->user->id." AND A.action='Invest/invest' AND A.status=2 ")
  		->join("left join", "fund_product AS B","A.product_id=B.id")
  		->limit(4)
  		->orderBy("A.id DESC")
  		->createCommand();
  		$invest_log = $query3->queryAll();
  		//数据包变量
  		$amount_total = $model_asset->balance + $invest_total + $income_current;
  		$session = yii::$app->session;
  		$session->open();
  		if(isset($session['last_time']))
  		{
  			$updated_at = $session['last_time'];
  		}
  		else 
  		{
  			$updated_at = $model->updated_at;
  		}
  		$phone = $model->phone;
  		$balance = $model_asset->balance;
  		$freeze = $model_asset->freeze;
  		 
  		return $infos_rar = compact("invest_total","income_total","amount_total","updated_at","phone","balance","freeze","invest_log","array_investlog","array_tradelog","pages","pages_trade","pages_offset","pages_trade_offset", "income_current","income_yesterday","experience_money");

  		 
  	}
	
}