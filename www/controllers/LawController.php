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
use backend\models\Article;

class LawController extends Controller
{
	public $defaultAction = 'law';
	
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
	

	//法律服务
    public function actionLaw()
    {
    	$infos = Article::findOne(['title'=>'法律服务']);
    	$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('law',compact("infos_rar","model","infos"));
    }
	
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
		$balance = $model_asset->balance;
		 
		$username = yii::$app->user->identity->username;
		return compact('updated_at','balance','username');
	}
}