<?php
/**
 * @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */
namespace www\controllers;

use frontend\actions\Port;
use yii\base\Controller;
use yii\base\ErrorException;
use frontend\actions\Balance;
use frontend\actions\yeepay;
use frontend\actions\member;
use common\models\UcenterMember;

class SmsController extends Controller
{
	
	//发送短信验证码接口
	public function actionSendcode()
	{
		if(!isset($_POST['CellPhone']))
		{
			echo '请输入手机号。';
			exit;
		}

		try 
		{
			$phone = $_POST['CellPhone'];
			$is_register = member::phoneIsRegister($phone);
			if($is_register)
			{
				echo '此手机号已注册，请直接登录';
				exit;
			}
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
    /**
     * 判定用户手机号是否存在
     * @return bool
     */
    public function actionSmsphone(){
        $phone = $_POST['CellPhone'];
        $res = UcenterMember::findOne([
            'phone' => $phone
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
     * 判定用短信验证码是否正确
     * @return bool
     */
    public function actionSmscode(){
    	$phone = $_POST['phone'];
    	$code = $_POST['code'];
    	try 
    	{
    		$res = Port::checkPhnoe($phone, $code);
    	}
    	catch (ErrorException $e)
    	{
    		return false;
    		exit;
    	}
    	if($res){
    		return true;
    		exit;
    	}else{
    		return false;
    		exit;
    	}
    }
	//发送短信验证码接口(忘记密码操作)
	public function actionSendcode_()
	{
		if(!$_POST['CellPhone'])
		{
			echo '请输入手机号。';
			exit;
		}
		try
		{
			$phone = $_POST['CellPhone'];
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
	
	//发送短信验证码接口(修改密码操作)
	public function actionSendcode_repass()
	{
		if(!$_POST['CellPhone'])
		{
			echo '请输入手机号。';
			exit;
		}
		try
		{
			$phone = $_POST['CellPhone'];
			$result = \frontend\actions\app\member::phoneCha($phone);
			if($result['errorNum'] == 0)
			{
				echo '验证码已发送，请注意查收。';
				exit;
			}
			elseif($result['errorNum'] == 1)
			{
				echo $result['errorMsg'];
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
		//$result = UcenterMember::find()->where(['invitation_code'=>$icode])->one();
		//if(count($result) == 1)
		$flag = \frontend\actions\app\member::verify_code($icode);
		if($flag)
		{
			echo '验证通过0001';
			exit;
		}
		else
		{
			echo '验证失败0002';
			exit;
		}
	}
	
}