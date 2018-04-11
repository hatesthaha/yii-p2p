<?php
namespace wechat\controllers;

use Yii;
use yii\base\Event;
use yii\helpers\Url;
use framework\helpers\Utils;
use common\models\UcenterMember;
use common\models\base\cms\Cat;
use common\models\base\cms\Link;
use common\models\base\fund\Product;
use frontend\actions\app\member;
use common\models\_LoginForm;
use frontend\actions\sinapay;
use common\models\base\asset\Info;
use frontend\actions\app\Invest;
use frontend\actions\App\AloneMethod;
use common\models\base\fund\Income;
use common\models\base\asset\Log;
use yii\data\Pagination;
use frontend\actions\Bcrdf;
/**
 * Site controller
 */
class MoneyController extends FrontendController
{
    public function behaviors()
    {
        return [
            'csrf' => [
                'class' => BCrdf::className(),
                'controller' => $this,
                'actions' => [
                    'loglist',//投资
                    
                ]
            ]
        ];
    }
    public function actionIndex(){
        $uid = Yii::$app->user->id;
        
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }elseif(empty(Yii::$app->user->id)){
            return $this->redirect(['site/signin']);
        }
        $isAuthentic = member::isAuthentic(yii::$app->user->id);
        if($isAuthentic['errorNum'] != 0)
        {
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('您还没有实名制认证')</script>";
            echo "<script>window.location.href='".\yii\helpers\Url::to(['site/step2'])."'</script>";
            exit;
        }
        //判断用户是否绑定银行卡
        $is_bind = sinapay::isBinding($uid);
        $model = UcenterMember::find()->where(['id'=>$uid])->one();
        if($is_bind['errorNum'] != 0)
        {
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('您还没有绑定银行卡');</script>";
            echo "<script>location.href='".\yii\helpers\Url::to(['site/bindcard'])."'</script>";
            exit;
        }
		//全部记录 
		$logs = AloneMethod::total_log($uid, 1, 15);
		$logs_pages = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$uid])->andWhere(['>','status','0'])->andWhere(['<>','step','0.00'])->asArray()->all();
		$logs_pages = ceil(count($logs_pages)/15);
		//$logs = Log::find()->select(['step','create_at','status'])->where(['member_id'=>$uid])->andWhere(['in','status',[1,2,3,4]])->orderBy('id desc')->asArray()->all();
		//充值
        $recharge = AloneMethod::recharge_log($uid, 1, 15);
		$recharge_pages = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$uid,'status'=>Log::STATUS_RECHAR_SUC])->asArray()->all();
		$recharge_pages = ceil(count($recharge_pages)/15);
		//投资
        $invest = AloneMethod::invest_log($uid, 1, 15);
		$invest_pages = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$uid,'status'=>Log::STATUS_INVEST_SUC])->asArray()->all();
		$invest_pages = ceil(count($invest_pages)/15);
		//赎回
        $ransom = AloneMethod::redeem_log($uid, 1, 15);
		$ransom_pages = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$uid,'status'=>Log::STATUS_REDEM_SUC])->asArray()->all();
		$ransom_pages = ceil(count($recharge_pages)/15);
		//提现
        $withdraw = AloneMethod::withdrawals_log($uid, 1, 15);
		$withdraw_pages = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$uid,'status'=>Log::STATUS_WITHDRAW_SUC])->asArray()->all();
		$withdraw_pages = ceil(count($recharge_pages)/15);

        return $this->view('index', compact('invest','recharge','withdraw','ransom','logs','logs_pages','recharge_pages','invest_pages','ransom_pages','withdraw_pages'));
    }

    public function actionLoglist(){
        if($_REQUEST)
        {
			$uid = yii::$app->user->id;
            $page = intval($_REQUEST[page]);
            $page_num = intval($_REQUEST[num]);
            $result = AloneMethod::total_log($uid, $page, $page_num);
            $datas = json_encode($result[data][data]);
			echo $datas;
        }
    }
	
	public function actionRechargelist(){
        if($_REQUEST)
        {
            $uid = yii::$app->user->id;
            $page = intval($_REQUEST[page]);
            $page_num = intval($_REQUEST[num]);
            $result = AloneMethod::recharge_log($uid, $page, $page_num);
            $datas = json_encode($result[data][data]);
            echo $datas;
        }
    }
	
	public function actionInvestlist(){
        if($_REQUEST)
        {
            $uid = yii::$app->user->id;
            $page = intval($_REQUEST[page]);
            $page_num = intval($_REQUEST[num]);
            $result = AloneMethod::invest_log($uid, $page, $page_num);
            $datas = json_encode($result[data][data]);
            echo $datas;
        }
    }
	
	public function actionRansomlist(){
        if($_REQUEST)
        {
            $uid = yii::$app->user->id;
            $page = intval($_REQUEST[page]);
            $page_num = intval($_REQUEST[num]);
            $result = AloneMethod::redeem_log($uid, $page, $page_num);
            $datas = json_encode($result[data][data]);
            echo $datas;
        }
    }
	
	public function actionWithdrawlist(){
	if($_REQUEST)
	{
		$uid = yii::$app->user->id;
		$page = intval($_REQUEST[page]);
		$page_num = intval($_REQUEST[num]);
		$result = AloneMethod::withdrawals_log($uid, $page, $page_num);
		$datas = json_encode($result[data][data]);
		echo $datas;
	}
}

}