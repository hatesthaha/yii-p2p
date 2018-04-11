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
use common\models\base\asset\Log;
use frontend\actions\Withdrawals;
use common\models\base\activity\RaiseCard;
use common\models\base\activity\Card;
use yii\data\Pagination;
use yii\db\Query;
use frontend\actions\AloneMethod;
use yii\base\ErrorException;
use common\models\post\SignIn;

class SignController extends Controller
{
	public $defaultAction = 'index';
	
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
	
	//未使用的礼券
  	public function actionIndex()
  	{
  		$uid = yii::$app->user->id;
  		$model = UcenterMember::find()->where(['id'=>$uid])->one();
  		//用户签到记录
  		$model_sign = SignIn::find()->where(['uid'=>$uid]);
  		
  		$pages_sign = new Pagination(['totalCount' => $model_sign->count(), 'pageSize' => '8']);
  		$sign_Infos = $model_sign->offset($pages_sign->offset)
  		->limit($pages_sign->limit)
  		->all();
  		
    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('index', compact("infos_rar","pages_sign","sign_Infos","model"));
  	}
  	
  	//使用礼券
  	public function actionUse()
  	{
  		$uid = yii::$app->user->id;
  		$raise_card_id = $_GET['id'];
  		//使用礼券
  		try
  		{
  			AloneMethod::useRaise($uid, $raise_card_id);
  			echo "<script>alert('您使用了一张礼券');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['coupon/nouse'])."'</script>";
  			exit;
  		}
  		catch (ErrorException $e)
  		{
  			$error = $e->getMessage();
  			echo "<script>alert('".$error."');</script>";
  			echo "<script>location.href='".\yii\helpers\Url::to(['coupon/nouse'])."'</script>";
  			exit;
  		}
  		
  	}
  	//用户中心数据包
  	public static function  Ucenter()
  	{
  		$model = UcenterMember::find()->where(['id'=>Yii::$app->user->id])->one();
  		//个人账户
  		$model_asset = Info::find()->where(['member_id'=>Yii::$app->user->id])->one();
  			
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
  		$balance = $model_asset->balance;
  			
  		$username = yii::$app->user->identity->username;
  		return compact('updated_at','balance','username');
  	}

}