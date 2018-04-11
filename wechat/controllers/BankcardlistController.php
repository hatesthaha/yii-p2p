<?php
namespace wechat\controllers;

use Yii;
use yii\base\Event;
use yii\helpers\Url;
use framework\helpers\Utils;
use frontend\actions\sinapay;
use framework\helpers\Jssdk;
use frontend\actions\App\AloneMethod;

/**
 * Site controller
 */
class BankcardlistController extends FrontendController
{
	//银行卡列表
	public function actionIndex(){
		$uid = Yii::$app->user->id;
		//判断用户是否绑定银行卡
		$is_bind = sinapay::isBinding($uid);
		$datas = '';
		if($is_bind[errorNum] == 0)
		{
			$datas = $is_bind[data];
		}
	
		return $this->view('index',compact("datas"));
	}
	
}
