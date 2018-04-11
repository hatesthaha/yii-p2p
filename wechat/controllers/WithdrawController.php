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
use common\models\base\fund\Order;
use frontend\actions\app\Withdrawals;
/**
 * Site controller
 */
class WithdrawController extends FrontendController
{
    public function actionIndex()
    {
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
        $uid = Yii::$app->user->id;
        $info = Info::find()->where(['member_id'=>$uid])->one();
        $limitConfig = sinapay::getsiteConfig();

        if($limitConfig){
            $withdraw_max = $limitConfig->withdraw_max;
            $withdraw_min = $limitConfig->withdraw_min;
            $withdraw_times = $limitConfig->withdraw_num;
        }

        return $this->view('index',compact('info','withdraw_max','withdraw_min','withdraw_times'));
    }
    public function actionDowithdraw()
    {
        $uid = Yii::$app->user->id;
        if($_POST){
            $money =$_POST['money'];

            $result = Withdrawals::withdraw($uid, $money);

            if($result['errorNum'] == 1){
                return $result['errorMsg'];
            }else{
                return '提现成功';
            }

        }
    }
}