<?php
/**
 * @2015年7月25日 09:11:57
 * @刘少华
 * @万虎网络
 */
namespace www\controllers;

use common\models\invation\AssetConfig;
use yii\web\Controller;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UcenterMember;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use frontend\actions\Withdrawals;
use yii\data\Pagination;
use yii\db\Query;
use frontend\actions\Balance;
use yii\base\ErrorException;
use frontend\actions\member;
use frontend\actions\Port;
use common\models\yeepay\Payment;
use backend\controllers\SiteController;
use common\models\base\fund\Income;
use common\models\base\fund\Order;
use frontend\actions\lianlian;
use framework\lianlian\lianlianClass;
use frontend\actions\Bcrdf;
use common\models\setting\Setting;
use frontend\actions\sinapay;
use common\models\base\experience\Rule;
use common\models\base\experience\Gold;
class MoneyController extends Controller
{
	public $defaultAction = 'bindcard';

	public function behaviors()
	{
		return [
			'csrf' => [
				'class' => BCrdf::className(),
				'controller' => $this,
				'actions' => [
				'returnurl' //支付返回
				]
			],
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					['allow' =>true,'roles' => ['@'],],
				],
			],
		];
	}

	//连连支付跳转
	public function actionReturnurl(){
		$ll = new lianlianClass();
		$test = $ll->urlReturn();
		if($test)
		{
			$this->redirect('recharge');
		}
		else 
		{
			echo '返回错误';
			exit;
		}
	}
	
	//账户充值
  	public function actionRecharge()
  	{
  		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
  		$uid = Yii::$app->user->id;
  		//判断用户是否实名认证
  		if(!member::isAuthentic($uid))
  		{
  			header("Content-type: text/html; charset=utf-8"); 
  			echo "<script>alert('您还没有实名制认证');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['setting/setting'])."'</script>";
  			exit;
  		}  
  		else 
  		{
  			$result = member::isAuthentic($uid);
  		}
  		//判断用户是否绑定银行卡
  		$is_bind = sinapay::isBinding($uid);
  		if($is_bind['errorNum'] != 0)
  		{
  			header("Content-type: text/html; charset=utf-8");
  			echo "<script>alert('您还没有绑定银行卡');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['money/bindcard'])."'</script>";
  			exit;
  		}  
  		elseif($is_bind['errorNum'] == 0)
  		{
  			$result_bind = $is_bind['data'];
  			$logo_bind = self::BankInfos();
  		}
  		//最大提现限额——（后台设置里取值）

		$limitConfig = sinapay::getsiteConfig();
		$deposit_max = 10000;
		$deposit_min = 5;
		$deposit_times = 3;
		if($limitConfig){
			$deposit_max = $limitConfig->deposit_max;
			$deposit_min = $limitConfig->deposit_min;
			$deposit_times = $limitConfig->deposit_num;
			
			//当日充值次数
			$today_num = Log::find()->where('member_id = '. $uid .'  AND create_at > '.strtotime(date("Y-m-d")).' AND status=1')->count();
			
		}


  		//用户充值操作
  		 if(isset($_POST['code']) && isset($_POST['ticket']) && isset($_POST['out_trade_no']))
  		{
  			$validate_code = $_POST['code'];
  			$out_trade_no = $_POST['out_trade_no'];
  			$ticket = $_POST['ticket'];
  			try
  			{
  				$info = sinapay::rechargeComfirm($out_trade_no, $ticket, $validate_code);
  				if($info['errorNum'] == 0)
  				{
  					echo "充值成功";
  					exit;
  				}
  				elseif($info['errorNum'] != 0)
  				{
  					echo $info['errorMsg'];
  					exit;
  				}
  			}
  			catch (ErrorException $e)
  			{
  				echo $e->getMessage();
  				exit;
  			}
  		}
  		
  		
  		elseif(isset($_POST['money']))
  		{
  			$money = $_POST['money']; //充值金额
  			try 
  			{
  				$info = sinapay::recharge($uid, $money);
  				
	  			echo json_encode($info);
	  			exit;
  			}
  			catch (ErrorException $e)
  			{
  				echo $e->getMessage();
  				exit;
  			}
  		} 
    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('recharge', compact("today_num","infos_rar","result","result_bind","model","logo_bind","deposit_times", "deposit_min","deposit_max"));
  	}
  	
  	//账户提现
  	public function actionWithdraw()
  	{
		Yii::trace("账户提现开始", "money");
  		$uid = yii::$app->user->id;
  		$isAuthentic = member::isAuthentic(yii::$app->user->id);
  		if(!$isAuthentic)
  		{
  			echo "<script>alert('您还没有实名制认证')</script>";
  			echo "<script>window.location.href='".\yii\helpers\Url::to(['setting/setting'])."'</script>";
  			exit;
  		}
  		$is_bind = sinapay::isBinding($uid);
  		if($is_bind['errorNum'] == 0)
  		{
  			$result_bind = $is_bind['data'];
  			$logo_bind = self::BankInfos();
  		}
  		elseif($is_bind['errorNum'] != 0)
  		{
  			header("Content-type: text/html; charset=utf-8");
  			echo "<script>alert('您还没有绑定银行卡');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['money/bindcard'])."'</script>";
  			exit;
  		}

		Yii::trace("账户提现2", "money");
		
  		//最大提现限额——（后台设置里取值）
		$limitConfig = sinapay::getsiteConfig();
		$withdraw_max = 10000;
		$withdraw_min = 5;
		$withdraw_times = 3;
		if($limitConfig)
		{
			$withdraw_max = $limitConfig->withdraw_max;
			$withdraw_min = $limitConfig->withdraw_min;
			$withdraw_times = $limitConfig->withdraw_num;
			
			//当日充值次数
			$today_num = Log::find()->where('member_id = '. $uid .'  AND create_at > '.strtotime(date("Y-m-d")).' AND status=4')->count();
			
		}
		
  		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
  		$uid = Yii::$app->user->id;
  		//个人账户
  		$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
  		//用户投资记录
  		$model_income = Income::find()->where('member_id='.Yii::$app->user->id)->all();
  			
  		//投资总额
  		$invest_total = 0;
  		$model_order = Order::find()->where('member_id='.Yii::$app->user->id." AND status=1")->all();
  		if(count($model_order) > 0)
  		{
  			foreach ($model_order as $K => $V)
  			{
  				$invest_total  += $V->money;
  			}
  		}
  			
  		//收益总额
  		$income_total = 0;
  		if(count($model_income) > 0)
  		{
  			foreach ($model_income as $K => $V)
  			{
  				$income_total  += $V->money;
  			}
  		}
  		//可提现金额
  		$amount_total = $model_asset->balance + $invest_total ;
  		$bank_card = Withdrawals::showCard($uid); //银行卡号
  		$balance = Withdrawals::showBlance($uid);  //显示余额
		Yii::trace('进入请求提现1', "money");

  		if(isset($_POST['money']))
  		{
			Yii::trace('进入请求提现2', "money");

			$money = $_POST['money'];
	    	try 
	    	{
	    	  $result = Withdrawals::withdraw($uid, $money);
	    	  if($result)
	    	  {
		    	  echo '提现成功';
		    	  exit;
	    	  }
	    	}
	    	catch (ErrorException $e)
	    	{
				Yii::trace($e->getMessage(), "money");
	    		echo $e->getMessage();
	    		exit;
	    	}
  		}

		Yii::trace('进入请求提现3', "money");

		$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('withdraw', compact("bank_card","balance","infos_rar","amount_total","invest_total","model","max","result_bind","logo_bind","withdraw_max","withdraw_min","withdraw_times","today_num"));
  	}
  	
  	//账户赎回
  	public function actionRedemption()
  	{
  		$uid = yii::$app->user->id;
  		$isAuthentic = member::isAuthentic(yii::$app->user->id);
  		if(!$isAuthentic)
  		{
  			echo "<script>alert('您还没有实名制认证')</script>";
  			echo "<script>window.location.href='".\yii\helpers\Url::to(['setting/setting'])."'</script>";
  			exit;
  		}
  		$is_bind = sinapay::isBinding($uid);
  		if($is_bind['errorNum'] == 0)
  		{
  			$result_bind = $is_bind['data'];
  			$logo_bind = self::BankInfos();
  		}
  		elseif($is_bind['errorNum'] != 0)
  		{
  			header("Content-type: text/html; charset=utf-8");
  			echo "<script>alert('您还没有绑定银行卡');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['money/bindcard'])."'</script>";
  			exit;
  		}
  		//最大赎回限额——（后台设置里取值）
  		$limitConfig = sinapay::getsiteConfig();
  		$redemption_max = 10000;
  		$redemption_min = 5;
  		$redemption_times = 3;
  		if($limitConfig)
  		{
  			$redemption_max = $limitConfig->ransom_max;
  			$redemption_min = $limitConfig->ransom_min;
  			$redemption_times = $limitConfig->ransom_num;
  				
  			//当日充值次数
  			$today_num = Log::find()->where('member_id = '. $uid .'  AND create_at > '.strtotime(date("Y-m-d")).' AND status=3')->count();
  				
  		}
  		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
  		$uid = Yii::$app->user->id;
  		//个人账户
  		$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
  		//用户投资记录
  		$model_income = Income::find()->where('member_id='.Yii::$app->user->id)->all();
  			
  		//投资总额
  		$invest_total = 0;
  		$model_order = Order::find()->where('member_id='.Yii::$app->user->id." AND status=1")->all();
  		if(count($model_order) > 0)
  		{
  			foreach ($model_order as $K => $V)
  			{
  				$invest_total  += $V->money;
  			}
  		}

		$invest_total  += $model_asset->profit;
  			
  		$bank_card = Withdrawals::showCard($uid); //银行卡号
  		$balance = Withdrawals::showBlance($uid);  //显示余额
  		//赎回操作
  		if(isset($_POST['money']))
  		{
  			$money = $_POST['money'];
  			try
  			{
  				$result = Withdrawals::User_redeem($uid, $money);
  				if($result)
  				{
  					echo '赎回成功';
  					exit;
  				}
  			}
  			catch (ErrorException $e)
  			{
  				echo $e->getMessage();
  				exit;
  			}
  		}
		$config = AssetConfig::find()->select(['id','ransom_num','ransom_min','ransom_max'])->where(['id' => '2'])->asArray()->one();

		$infos_rar = $this->Ucenter();	//用户数据包
  		return $this->render('redemption', compact("bank_card","balance","infos_rar","amount_total","invest_total","model","result_bind","logo_bind","redemption_max","redemption_min","today_num","redemption_times",'config'));
  	}
  	
  	//绑定银行卡
  	public function actionBindcard()
  	{
  		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
  		$uid = yii::$app->user->id;
  		$is_bind = sinapay::isBinding($uid);
  		if($is_bind['errorNum'] == 0)
  		{
  			$result_bind = $is_bind['data'];
  			$logo_bind = self::BankInfos();
  		}
  		$result = member::isAuthentic($uid);
  		//接收验证码后进行银行卡绑定
  		try
  		{
  			if(isset($_POST['bankcard']) && isset($_POST['idcard']) && isset($_POST['username'])&& isset($_POST['phone']) && isset($_POST['code']) && isset($_POST['ticket']) && isset($_POST['request_no']))
  			//if($_POST['bankcard'] && $_POST['idcard'] && $_POST['username'] && $_POST['phone'] && $_POST['code'] && $_POST['hidden_code'])
  			{
  				$valid_code = $_POST['code'];
  				$request_no = $_POST['request_no'];
  				$ticket = $_POST['ticket'];
  				$info = sinapay::bankCardAdvance($request_no, $ticket, $valid_code);
  				if($info['errorNum'] == 0)
  				{
  					//领取体验金
  					$rid = Rule::find()->where(['title'=>'绑定银行卡','status'=>1])->one()->id;
  					$r_money = Rule::find()->where(['title'=>'绑定银行卡','status'=>1])->one()->money;
  					$model_gold = new Gold();
  					$model_gold->rid = $rid;
  					$model_gold->money = $r_money;
  					$model_gold->uid = yii::$app->user->id;
		        	$model_gold->created_at = strtotime("now");
  					$model_gold->save();
  					echo "绑卡成功";
  					exit;
  				}
  				elseif($info['errorNum'] != 0)
  				{
  					echo $info['errorMsg'];
  					exit;
  				}
  		
  			}
  		
  		}
  		catch (ErrorException $e)
  		{
  			echo $e->getMessage();
  			exit;
  		}
  		//发送验证码和请求ID
  		 try 
  		{
  		
	  		if(isset($_POST['bankcard']) && isset($_POST['idcard']) && isset($_POST['username'])&& isset($_POST['phone']))
	  		{
	  			$cardno = $_POST['bankcard'];
	  			$idcardno = $_POST['idcard'];
	  			$username = $_POST['username'];
	  			$phone = $_POST['phone'];
	  			try
	  			{
	  				$info = sinapay::bindingBankCard($uid, $cardno, $phone);
	  				
  					echo json_encode($info);
  					exit;
	  			}
	  			catch (ErrorException $e)
	  			{
	  				echo $e->getMessage();
	  				exit;
	  			}
	  		}
  			
  		}
  		catch (ErrorException $e)
  		{
  			echo $e->getMessage();
  			exit;
  		} 
  		if(isset($_POST['bankcard']) && isset($_POST['phone']))
  		{
  			$cardno = $_POST['bankcard'];
  			$phone = $_POST['phone'];
  			$info = Balance::bindbankcard2($uid, $cardno,$phone);
  			if($info)
  			{
  			echo '绑卡成功';
  		
  			exit;
  			}
  		}
  		
  		$infos_rar = $this->Ucenter();	//用户数据包
  		return $this->render('bindcard', compact("infos_rar","result","result_bind","model","logo_bind"));
  	}
  	
 
	
  	//用户中心数据包
  	public static function  Ucenter()
  	{
  		$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
  		//个人账户
  		$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
  		 
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
  		if(isset($model_asset->balance))
  		{
  			$balance = $model_asset->balance;
  		}
  		 
  		$username = yii::$app->user->identity->username;
  		return compact('updated_at','balance','username');
  	}
  	
  	//银行卡信息数据包
  	public static function BankInfos()
  	{
  		$uid = yii::$app->user->id;
  		$infos = sinapay::isBinding($uid);
  		$bank_code = $infos['data']['bank_code'];
  		$logo = '';
  		switch ($bank_code)
  		{
  			case 'ICBC' :
  				$logo = 'ICBC';
  				break;
  			case 'ABC' :
  				$logo = 'ABC';
  				break;
  			case 'BOC' :
  				$logo = 'BOC';
  				break;
  			case 'CCB' :
  				$logo = 'CCB';
  				break;
  			case 'COMM' :
  				$logo = 'COMM';
  				break;
  			case 'SPDB' :
  				$logo = 'SPDB';
  				break;
  			case 'CIB' :
  				$logo = 'CIB';
  				break;
  			case 'CEB' :
  				$logo = 'CEB';
  				break;
  			case 'CMBC' :
  				$logo = 'CMBC';
  				break;
  			case 'CITIC' :
  				$logo = 'CITIC';
  				break;
  			case 'CMB' :
  				$logo = 'CMB';
  				break;
  			case 'PSBC' :
  				$logo = 'PSBC';
  				break;
  			case 'SZPAB' :
  				$logo = 'SZPAB';
  				break;
  			case 'BCCB' :
  				$logo = 'BCCB';
  				break;
  			case 'GDB' :
  				$logo = 'GDB';
  				break;
  			case 'CBHB' :
  				$logo = 'CBHB';
  				break;
  			case 'HXB' :
  				$logo = 'HXB';
  				break;
  			case 'BOS' :
  				$logo = 'BOS';
  				break;
  			case 'NJCB' :
  				$logo = 'NJCB';
  				break;
  			case 'CZB' :
  				$logo = 'CZB';
  				break;
  		}
  		return $logo;
  	}
  	
  	
}