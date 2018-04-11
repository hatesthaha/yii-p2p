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
use common\models\base\site\VerifyCode;
/**
 * Site controller
 */
class RechargeController extends FrontendController
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

        $info = Info::find()->where(['member_id'=>$uid])->one();
        //最大提现限额——（后台设置里取值）

		$phone = UcenterMember::find()->where(['id'=>$uid])->one()->phone;
        $limitConfig = sinapay::getsiteConfig();
        $deposit_max = 10000;
        $deposit_min = 5;
        $deposit_times = 3;
        if($limitConfig){
            $deposit_max = $limitConfig->deposit_max;
            $deposit_min = $limitConfig->deposit_min;
            $deposit_times = $limitConfig->deposit_num;
        }
        return $this->view('index',compact('info',"deposit_times", "deposit_min","deposit_max","phone"));
    }
    public function actionDorecharge(){
        $uid = Yii::$app->user->id;
        if($_POST){


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
                $phone = Info::find()->andWhere(['member_id'=>$uid])->one()->bank_card_phone;
                //60秒发送一次验证码
                $time = time()-60;
                $count = VerifyCode::find()
                    ->andWhere([
                        'type' => 1,
                        'field' => $phone,
                        'status' => -1
                    ])->orderBy('b_time desc')->one();
                if($time < $count['b_time']){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '请勿重复点击',
                        'data' =>null
                    );
                    echo json_encode($return);
                    exit;
                }
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
            }else{
                echo '您提交的信息不完整';
                exit;
            }

        }

    }
}