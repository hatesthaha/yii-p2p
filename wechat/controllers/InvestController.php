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
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class InvestController extends FrontendController
{
	
    public function actionIndex()
    {
		if(Yii::$app->user->isGuest)
		{
			return $this->redirect(array('/site/signin'));
		}
        $uid = Yii::$app->user->id;
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
        if($_GET){
            $uid = Yii::$app->user->id;
            $info = Info::find()->where(['member_id'=>$uid])->one();
            $product = Product::find(['id'=>$_GET['id']])->one();
            //项目可投金额
            $kmoney = \frontend\actions\App\Invest::kmoney($_GET['id'])[data];
            $limitConfig = sinapay::getsiteConfig();

            if($limitConfig){
                $invest_max = $limitConfig->invest_max;
                $invest_min = $limitConfig->invest_min;
                $invest_times = $limitConfig->invest_num;
            }
            return $this->view('index',compact('info','product','invest_max','invest_min','invest_num','kmoney'));
        }

    }
    public function actionDoinvest(){
        $uid = Yii::$app->user->id;
        if($_POST){
            $money =$_POST['money'];
            $pid = $_POST['product_id'];
            $result = Invest::Invest($uid, $pid, $money);
            Invest::gold($uid,$money);//体验金

            if($result['errorNum'] == 1){
                return $result['errorMsg'];
            }else{
                return '您已成功完成了投资';
            }

        }
    }
}