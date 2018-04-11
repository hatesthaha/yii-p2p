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
use common\models\_LoginForm;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\actions\Port;
use frontend\actions\yeepay;
use frontend\actions\yeepayClass;
use common\models\UcenterMember;
use frontend\actions\member;
use common\models\base\asset\Info;
use yii\web\UploadedFile;
use common\models\setting\Setting;
use frontend\actions\sinapay;

class SettingController extends Controller
{
	public $defaultAction = 'setting';
	
	public function behaviors()
	{
		return [
				'access' => [
						'class' => \yii\filters\AccessControl::className(),
						'only' => ['setting','repass','repassing'],
						'rules' => [
								['allow' =>true,'roles' => ['@'],],
						],
				],
		];
	}
	

	//个人设置
	public function actionSetting()
	{
		if(\yii::$app->user->isGuest)
		{
			$this->redirect(['site/login']);
		}
		$uid = yii::$app->user->id;
		$is_Authentic = member::isAuthentic($uid);
		$model = UcenterMember::find()->where("id=".$uid)->one();
		if(isset($_POST['prepassword']) && isset($_POST['newpassword']) && isset($_POST['repeatpassword']))
		{
			$_model = UcenterMember::findIdentity($uid);
			if( !yii::$app->security->validatePassword($_POST['prepassword'], $_model->password_hash))
			{
				echo '原始密码错误';
				exit;
			}
			elseif($_POST['newpassword'] == $_POST['repeatpassword'])
			{
				$_model->password_hash = Yii::$app->security->generatePasswordHash($_POST['newpassword']);
				$app_pwd = md5(sha1($_POST['newpassword']).time());
				$_model->app_pwd = $app_pwd;
				$_model->save(false);
				echo '密码修改成功';
				exit;
			}
			else
			{
				echo '两次密码不一致';
				exit;
			}
	
		}
		if(isset($_POST['realname']) && isset($_POST['idcard']))
		{
			$realname = $_POST['realname'];
			$idcard = $_POST['idcard'];
			try
			{
				$idcard_result = Port::baiduIdentity($idcard);
				if(!$idcard)
				{
					echo '身份认号不匹配，请核对后重新输入';
					exit;
				}
			}
			catch (ErrorException $e)
			{
				echo '认证失败';
				exit;
			}
			try
			{
				$result = member::authentication($uid,$realname, $idcard);
				if($result)
				{
					echo '身份认证成功';
					exit;
				}
			}
			catch (ErrorException $e)
			{
				echo $e->getMessage();
				exit;
			}
	
		}
		if(isset($_POST['UcenterMember']['person_face']))
		{
			$model->person_face = UploadedFile::getInstance($model, 'person_face');
			if ($model->person_face)
			{
				 
				if ($model->validate()) {
					$old_face = UcenterMember::findOne(['id'=>$uid])->person_face;	//旧头像
					$init_face = Setting::findOne(['code'=>'img'])->value;	//初始头像
					$Name = mt_rand(1100,9900) .time() .'.'. $model->person_face->extension;
					$model->person_face->saveAs('upload/'.$Name);
					//保存头像名到数据表
					$model->person_face = $Name;
					if($model->update())
					{
						//若不是初始头像，删除旧头像。
						if($old_face != $init_face)
						{
							@unlink('../web/upload/'.$old_face);
						}
						header("Content-type: text/html; charset=utf-8");
						echo '<script>alert("头像修改完成~");</script>';
					}
	
				}
			}
		}
		$infos_rar = $this->Ucenter();	//用户数据包
		return $this->render('setting',compact("infos_rar","model","model_password","is_Authentic"));
	}
	
	//修改密码第一步
	public function actionRepass()
	{
		$uid = yii::$app->user->id;
		//判断验证码正确性
		if(isset($_POST['CellPhone']) && isset($_POST['validate_code']))
		{
			$phone = $_POST['CellPhone'];
			$code = $_POST['validate_code'];
			try
			{
				$result = Port::checkPhnoe($phone, $code);
				if(is_bool($result))
				{
					
					$session = yii::$app->session;
					$session->open();
					$session['phone'] = $phone;
					
					echo '验证通过0001';
					exit;
				}
				else 
				{
					echo '验证失败0002';
					exit;
				}
			}
			catch (ErrorException $e)
			{
				echo $e->getMessage();
				exit;
			}
		}
		
		return $this->render('repass',compact("infos_rar","model"));
	}
	//修改密码第二步
	public function actionRepassing()
	{
		$uid = yii::$app->user->id;
		$session = yii::$app->session;
		$session->open();
		$phone = $session['phone'];
		if(isset($_POST['password']) && isset($_POST['repeatpassword']))
		{
			$session->remove("phone");
			$password = $_POST['password'];
			$repeatpassword = $_POST['repeatpassword'];
			
			$model = UcenterMember::find()->where(['phone'=>$phone])->one();
			$row = UcenterMember::find()->where(['phone'=>$phone])->count();
				
			if($row == 1)
			{
				$model->password_hash = yii::$app->security->generatePasswordHash($password);
				$app_pwd = md5(sha1($password).time());
				$model->app_pwd = $app_pwd;
				if($model->save(false))
				{
					Yii::$app->user->logout();
					echo '密码修改成功0001';
					exit;
				}
				else
				{
					echo '密码修改失败，请联系客服！';
					exit;
				}
			}
			else
			{
				$this->redirect(['account/overview']);
				exit;
			}
		
		}
		return $this->render('repassing');
	}
	//修改密码第三步
	public function actionRepassed()
	{
		return $this->render('repassed');
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