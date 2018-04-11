<?php
namespace wechat\controllers;

use common\models\base\asset\Info;
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
use common\models\base\fund\Order;
use common\models\post\SignIn;
/**
 * Site controller
 */
class MemberController extends FrontendController
{
    public function actionInfoview()
    {
        $uid = Yii::$app->user->id;
        $info = Info::find()->where(['member_id'=>$uid])->one();

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
        return $this->view('infoview',compact('info','invest_total'));
    }
    public function actionSignin()
    {
        $uid = Yii::$app->user->id;
        //用户签到记录
        $all = SignIn::find()->where(['uid'=>$uid])->all();
        $signin = SignIn::find()->select('uid,sign_in_money,sign_in_time,sum(sign_in_money) as smoney')->where(['uid'=>$uid])->asArray()->one();
        $count = SignIn::find()->where(['uid'=>$uid])->count();
        $yesterday_sign_in = member::get_yesterday_sign_in();
        $today_sign_in = member::get_today_sign_in();

        $result = member::is_sign_today(yii::$app->user->id);
        if($result['errorNum'] == 1)
        {
            $isCheckin = true;
        }
        elseif($result['errorNum'] == 0)
        {
            $isCheckin = false;
        }
        return $this->view('signin',compact('signin','count','all','yesterday_sign_in','today_sign_in','isCheckin'));
    }
    public function actionNewsign(){
        $yesterday_sign_in = member::get_yesterday_sign_in();
        $today_sign_in = member::get_today_sign_in();

        $result = member::is_sign_today(yii::$app->user->id);
        if($result['errorNum'] == 1)
        {
            $isCheckin = true;
        }
        elseif($result['errorNum'] == 0)
        {
            $isCheckin = false;
        }
        return $this->view('newsign',compact('yesterday_sign_in','today_sign_in','isCheckin'));
    }
    //用户签到
    public function actionCheckin()
    {
        if(!yii::$app->user->isGuest)
        {
            $uid = yii::$app->user->id;

            $checkin_total = count(SignIn::find()->where('sign_in_time >='.strtotime(date("Y-m-d")))->all());//本日签到总人数

            try
            {
                $info = member::signIn(yii::$app->user->id, '');
                echo json_encode($info);
                exit;
            }
            catch (ErrorException $e)
            {
                echo $e->getMessage();
                exit;
            }

            echo $checkin_total;
            exit;

        }
    }
    public function actionRepass()
    {
        return $this->view('repass');
    }
    public function actionDorepass()
    {
 $uid = Yii::$app->user->id;

        if($_POST){

            $member = member::changePassword($uid,$_POST['oldpass'],$_POST['password'],$_POST['repassword']);
            if($member['errorNum'] == 1){
                return $member['errorMsg'];
            }else{
                Utils::ensureOpenId();
                $openId = Yii::$app->request->get('open_id');
                if($openId){
                    $model = UcenterMember::findOne(['openid' => $openId]);
                    if($model){
                        $model->openid = '';
                        $model->save();
                    }
                
                }
                Yii::$app->user->logout();
                return '修改成功';
            }
        }
    }
    public function actionSetting()
    {
        $uid = Yii::$app->user->id;
        $user = UcenterMember::find()->andWhere(['id'=>$uid])->one();
        return $this->view('setting',compact('user'));
    }

}