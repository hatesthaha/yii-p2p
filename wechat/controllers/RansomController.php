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
use frontend\actions\App\AloneMethod;
/**
 * Site controller
 */
class RansomController extends FrontendController
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
        $limitConfig = sinapay::getsiteConfig();

        if($limitConfig){
            $ransom_max = $limitConfig->ransom_max;
            $ransom_min = $limitConfig->ransom_min;
            $ransom_times = $limitConfig->ransom_num;
        }

        $collect = AloneMethod::user_collect($uid)[data];  //在投金额和在投收益
        $red_packet = member::get_user_red_packet($uid)[data];
        //个人账户
        $model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
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
        return $this->view('index',compact('invest_total','ransom_max','ransom_min','ransom_times','collect','red_packet'));
    }
    public function actionDoransom(){
        $uid = Yii::$app->user->id;
        if($_POST){
            $money =$_POST['money'];

            $result = Withdrawals::User_redeem($uid, $money);

            if($result['errorNum'] == 1){
                return $result['errorMsg'];
            }else{
                return '赎回成功';
            }

        }
    }
}