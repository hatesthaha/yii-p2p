<?php
namespace wechat\controllers;

use Yii;
use yii\web\Controller;
use common\models\UcenterMember;
use frontend\actions\member;
use frontend\actions\Port;
use common\models\base\site\VerifyCode;

class PhoneController extends Controller
{
    /**
     * 判定用户手机号是否存在
     * @return bool
     */
    public function actionSmsphone(){
        $phone = $_POST['CellPhone'];
        $res = UcenterMember::findOne([
            'username' => $phone
        ]);
        if($res){
            return false;
            exit;
        }else{
            return true;
            exit;
        }
    }
    /**
     * 判定用户手机验证码是否正确
     * @return bool
     */
    public function actionCodecheck(){
        $phone = $_POST['CellPhone'];
        $code = $_POST['code'];
        $return = \frontend\actions\app\Port::checkPhnoe($phone,$code);
        if($return['errorNum'] == 0){
            return true;
            exit;
        }else{
            return false;
            exit;
        }
    }
    //发送短信验证码接口
    public function actionSendcode()
    {
        if(!isset($_POST['CellPhone']))
        {
            echo '请输入手机号。';
            exit;
        }
        $phone = $_POST['CellPhone'];

        //60秒发送一次验证码
        $time = time()-60;
        $count = VerifyCode::find()
            ->andWhere([
                'type' => 1,
                'field' => $phone,
                'status' => -1
            ])->orderBy('b_time desc')->one();
        if($time < $count['b_time']){
            echo '请勿重复点击';
            exit;
        }
        try
        {

            $result = Port::ValidatePhone($phone);
            if($result)
            {
                echo '验证码已发送，请注意查收。';
                exit;
            }
        }
        catch (ErrorException $ex)
        {
            echo $ex->getMessage();
            exit;
        }
    }
    //判断邀请码合法性
    public function actionInvitation_code()
    {
        if(!$_POST['icode'])
        {
            echo '请输入邀请码。';
            exit;
        }
        $icode = $_POST['icode'];

        $flag = \frontend\actions\app\member::verify_code($icode);
        if($flag)
        {
            echo '验证通过';
            exit;
        }
        else
        {
            echo '验证失败';
            exit;
        }
    }
}