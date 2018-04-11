<?php
/**
 * @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */

namespace www\controllers;

use frontend\actions\AloneMethod;
use frontend\actions\App;
use frontend\actions\Appcheck;
use frontend\actions\Asset;
use frontend\actions\Balance;
use frontend\actions\Fund;
use frontend\actions\Invest;
use frontend\actions\member;
use frontend\actions\Port;
use frontend\actions\Withdrawals;
use frontend\actions\yeepay;
use frontend\actions\yeepayClass;
use Yii;
use common\models\_LoginForm;
use www\models\PasswordResetRequestForm;
use www\models\ResetPasswordForm;
use www\models\SignupForm;
use www\models\ContactForm;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UcenterMember;
use common\models\base\fund\Product;
use common\models\base\activity\CheckinLog;
use common\models\base\activity\RaiseCard;
use common\models\base\asset\Info;
use common\models\base\fund\Income;
use common\models\base\fund\Order;
use yii\db\Query;
use common\models\base\asset\Log;
use yii\data\Pagination;
use common\models\setting\Setting;
use yii\web\UploadedFile;
use www\models\ChangePasswordForm;
use common\models\base\activity\Card;
use framework\helpers\Utils;
use common\models\base\cms\Cat;
use common\models\base\cms\Link;
use backend\models\Category;
use backend\models\Article;
use common\models\base\experience\Rule;
use common\models\base\experience\Gold;
use frontend\actions\sinapay;
use common\models\post\SignIn;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [/* 
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
    	$title = '';
    	$reserve = '';
    	try 
    	{
	    	$title = Setting::findOne(['code'=>'siteTitle'])->value; // 网站title
	    	$reserve = Setting::findOne(['code'=>'reserve'])->value;  //储备金额
    	}
    	catch (ErrorException $e)
    	{
    		
    	}
    	
    	//媒体报道
    	try 
    	{
	    	$cat_id = Cat::find()->where(['name'=>'媒体报道','status'=>1])->one()->id;
	    	if($cat_id)
	    	{
	    		$media = Link::find()->where(['cat_id'=>$cat_id,'status'=>1])->asArray()->all();
	    	}
    	}
    	catch (ErrorException $e)
    	{
    		$media = "";
    	}
    	
    	//合作伙伴
    	try
    	{
    		$cat_id = Cat::find()->where(['name'=>'合作伙伴','status'=>1])->one()->id;
    		$partner = Link::find()->where(['cat_id'=>$cat_id,'status'=>1])->asArray()->all();
    	}
    	catch (ErrorException $e)
    	{
    		$partner = "";
    	}
    	
    	//投资帮助
    	/* try
    	{
    		$parent_id = Category::findOne(['title'=>'帮助中心','status'=>1])->id;
    		$category = Category::find()->where(['parent_id'=>$parent_id,'status'=>1])->asArray()->all();
    		$left = array();
    		foreach ($category as $K=>$V)
    		{
    			$left[] = Article::find()->where(['category_id'=>$V['id'],'status'=>1])->asArray()->all();
    		}
    	}
    	catch (ErrorException $e)
    	{
    		$left = array();
    	} */
    	
    	$hover='hover';
    	//项目列表
		//TODO
//		$model = Product::find()->limit(4)->orderBy('start_at DESC')->all();
    	$model = Product::find()->limit(4)->orderBy('start_at DESC')->where('create_at >= 1441641600 ')->all();
    	//是否签到
    	$isCheckin = false;
    	$checkin_total = count(SignIn::find()->where('create_at >='.strtotime(date("Y-m-d")))->all());//本日签到总人数
    	$yesterday_total = \frontend\actions\app\member::get_yesterday_sign_in()['data']['count'];

    	//获取昨日签到情况 TODO
		$yesterday_sign_in = \frontend\actions\app\member::get_yesterday_sign_in();
		//获取今日签到情况 TODO
		$today_sign_in = \frontend\actions\app\member::get_today_sign_in();

    	//昨日在投收益排名
    	$command = (new \yii\db\Query())
    	->select(['ucenter_member.real_name','fund_income.smoney as money'])
    	->from('fund_income')
    	->where("fund_income.created_at >=".strtotime(date("Y-m-d")))
    	->join('left join','ucenter_member','fund_income.member_id = ucenter_member.id')
    	->limit(6) 	  //取多少条数据
    	->groupBy('fund_income.member_id')
    	->orderBy('money DESC')
    	->createCommand();
    	$rank = $command->queryAll();
    	
    	//近30天收益排名
		//TODO
		$test_time = strtotime("-1 month") > "1441641600" ? strtotime("-1 month") : '1441641600';

    	$command_month = (new \yii\db\Query())
    	->select(['ucenter_member.real_name','sum(fund_income.smoney) as money'])
    	->from('fund_income')
//    	->where("fund_income.created_at >=".strtotime("-1 month"))
    	->where("fund_income.created_at >=".$test_time)
    	->join('left join','ucenter_member','fund_income.member_id = ucenter_member.id')
    	->limit(6) 	  //取多少条数据
    	->groupBy('fund_income.member_id')
    	->orderBy('money DESC')
    	->createCommand();
    	$rank_month = $command_month->queryAll();
    	if(!Yii::$app->user->isGuest)
    	{
	    	$result = \frontend\actions\app\member::is_sign_today(yii::$app->user->id);
	    	if($result['errorNum'] == 1)
	    	{
	    		$isCheckin = true;
	    	}
	    	elseif($result['errorNum'] == 0)
	    	{
	    		$isCheckin = false;
	    	}
		    	
    	}
        
    	return $this->render('index', ['yesterday_sign_in' => $yesterday_sign_in,'today_sign_in' => $today_sign_in ,'model' => $model,'isCheckin' => $isCheckin,'checkin_total' =>$checkin_total, 'yesterday_total' =>$yesterday_total, 'rank'=>$rank, 'rank_month' =>$rank_month,'hover'=>$hover,'title'=>$title,'reserve'=>$reserve,'media'=>$media,'partner'=>$partner]);
    }


    //用户登录
    public function actionLogin()
    {
        if(!yii::$app->user->isGuest)
        {
        	$this->redirect(['account/overview']);
        }
        $model = new _LoginForm();
        if (!empty($_POST['phone']) && !empty($_POST['password']))
	        {
		        $username = $_POST['phone'];
		        $password = $_POST['password'];
		        
	        	if($model->login($username,$password))
	        	{
	        		
	        	}
	        }
        else 
        {
            return $this->render('login', compact("model"));
        }
    }

    //退出操作
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    //忘记密码
    public function actionForgot()
    {
    	if(isset($_POST['CellPhone']) && isset($_POST['validate_code']))
    	{
    		$phone = $_POST['CellPhone'];
    		$code = $_POST['validate_code'];
    		try 
    		{
    			$result = Port::checkPhnoe($phone, $code);
    			if(is_bool($result))
    			{
    				$row = UcenterMember::find()->where('phone='.$phone)->count();
    				if($row == 1)
    				{
    					$session = yii::$app->session;
    					$session->open();
    					$session['phone'] = $phone;
    					$uid = UcenterMember::find()->where('phone='.$phone)->asArray()->one()['id'];
    					if(member::isAuthentic($uid))
    					{
    						echo '已实名制认证0001';
    						exit;
    					}
    					else 
    					{
    						echo '未实名制认证0002';
    						exit;
    					}
    				}
    				else 
    				{
    					echo '用户未注册';
    					exit;
    				}
    			}
    		}
    		catch (ErrorException $e)
    		{
    			echo $e->getMessage();
    			exit;
    		}
    	}
    	return $this->render('forgot');
    }
    
    //已实名认证的用户找回密码
    public function actionStep1()
    {
    	$session = yii::$app->session;
    	$session->open();
    	$phone = $session['phone'];
    	if(isset($_POST['name']) && isset($_POST['idcard']) && isset($_POST['password']) && isset($_POST['repeatpassword']))
    	{
    		$session->remove("phone");
    		$name = $_POST['name'];
    		$idcard = $_POST['idcard'];
    		$password = $_POST['password'];
    		$repeatpassword = $_POST['repeatpassword'];
    		$model = UcenterMember::find()->where(['real_name'=>$name,'idcard'=>$idcard,'phone'=>$phone])->one();
    		$row = UcenterMember::find()->where(['real_name'=>$name,'idcard'=>$idcard,'phone'=>$phone])->count();
    		if($row == 1)
    		{
    			$model->password_hash = yii::$app->security->generatePasswordHash($password);
				$app_pwd = md5(sha1($password).time());
				$model->app_pwd = $app_pwd;
    			if($model->save(false))
    			{
    				header("Content-type: text/html; charset=utf-8");
    				echo "<script>alert('密码修改成功')</script>";
    				echo "<script>window.location.href='".\yii\helpers\Url::to(['site/login'])."'</script>";
    				exit;
    			}
    		}
    		else 
    		{
    			header("Content-type: text/html; charset=utf-8");
    			echo "<script>alert('密码修改失败，请联系客服！')</script>";
    			echo "<script>window.location.href='".\yii\helpers\Url::to(['site/index'])."'</script>";
    			exit;
    		}
    	}
    	return $this->render('step1');
    }
    
    //未实名认证的用户找回密码
    public function actionStep2()
    {
    	$session = yii::$app->session;
    	$session->open();
    	$phone = $session['phone'];
    	if(isset($_POST['password']) && isset($_POST['repeatpassword']))
    	{
    		$session->remove("phone");
    		$repeatpassword = $_POST['repeatpassword'];
    		$model = UcenterMember::find()->where("phone=".$phone)->one();
    		$row = UcenterMember::find()->where("phone=".$phone)->count();

    		$password = $_POST['password'];
    		if($row == 1)
    		{
    			$model->password_hash = yii::$app->security->generatePasswordHash($password);
				$app_pwd = md5(sha1($password).time());
				$model->app_pwd = $app_pwd;
    			if($model->save(false))
    			{
    				header("Content-type: text/html; charset=utf-8");
    				echo "<script>alert('密码修改成功')</script>";
    				echo "<script>window.location.href='".\yii\helpers\Url::to(['site/login'])."'</script>";
    				exit;
    			}
    		}
    		else
    		{
    			header("Content-type: text/html; charset=utf-8");
    			echo "<script>alert('密码修改失败，请联系客服！')</script>";
    			echo "<script>window.location.href='".\yii\helpers\Url::to(['site/index'])."'</script>";
    			exit;
    		}
    		
    	}
    	return $this->render('step2');
    }


    //修改  by liushaohua 2015年7月27日 08:56:07
    //注册操作
	public function actionSignup()
	{
		//判断注册URL中是否包含邀请码参数
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
			//$_code_source = UcenterMember::find()->where(['invitation_code'=>$code])->one();
			//if(count($_code_source) != 1)
			$flag = \frontend\actions\app\member::verify_code($code);
			if (!$flag) {
				header("Content-type: text/html; charset=utf-8");
				echo "<script>alert('来源链接不合法！')</script>";
				echo "<script>window.location.href='" . \yii\helpers\Url::to(['site/signup']) . "'</script>";
				exit;
			}
		}
		$model = new SignupForm();
		//是否邀请注册
		$test_invite = 1;
		if ($model->load(Yii::$app->request->post())) {
			if ($_POST['SignupForm']['username'] && $_POST['SignupForm']['validate_code'] && $_POST['SignupForm']['password'] && $_POST['SignupForm']['password_repeat']) {
				$invite_code = $_POST ["SignupForm"]['invitation_code'];
				//使用测试邀请码
				if ($test_invite) {
					//使用测试邀请
					$flag = \frontend\actions\app\member::verify_code($invite_code);
					//验证码通过
					if (!$flag) {
						echo "<script>alert('" . '邀请码错误' . "')</script>";
					}
				}

				$_phone = $_POST['SignupForm']['username'];
				$_code = $_POST['SignupForm']['validate_code'];
				try {
					$result = Port::checkPhnoe($_phone, $_code);

					if (is_bool($result)) {

					}
				} catch (ErrorException $e) {
					header("Content-type: text/html; charset=utf-8");
					echo "<script>alert('" . $e->getMessage() . "')</script>";
					echo "<script>location.href='" . \yii\helpers\Url::to(['site/signup']) . "'</script>";
					exit;
				}

				$user = new UcenterMember();
				$user->username = $_POST ["SignupForm"]['username'];
				$user->phone = $_POST ["SignupForm"]['username'];
				$user->invitation_code = $_POST ["SignupForm"]['invitation_code'];
				$password = $_POST ["SignupForm"]['password'];
				$user->setPassword($password);
				$user->create_ip = Yii::$app->request->userIp;
				$app_pwd = md5(sha1($password) . time());
				$user->app_pwd = $app_pwd;
				try {
					$area = self::get_area(Yii::$app->request->userIp);
					$user->create_area = $area;
				} catch (ErrorException $e) {

				}
				$user->generateAuthKey();

				if ($user->save()) {
					$uid = $user['id'];
					if ($test_invite) {
						//使用验证码
						$flag = \frontend\actions\app\member::verify_code($invite_code);
						$invite_flag = \frontend\actions\app\member::use_code($flag, $uid);
						if (!$invite_flag) {
							$return = array(
								'errorNum' => '1',
								'errorMsg' => '注册失败',
								'data' => null
							);
							return $return;
						}
					}

					if (Yii::$app->getUser()->login($user)) {
						$newModel = UcenterMember::find()->where(['id' => Yii::$app->user->id])->one();
						//$newModel->invitation_id= \yii::$app->params['invitation_id'];
						$invitation_code = Utils::createcode();
						$newModel->invitation_code = $invitation_code;
						$newModel->person_face = Setting::find()->where("code='img'")->one()->value;
						if ($_POST['url_code']) {
							$code_source = UcenterMember::find()->where(['invitation_code' => $_POST["url_code"]])->asArray()->one();
							if ($code_source) {
								$newModel->invitation_id = $code_source['id'];
							}
							//好友注册领取体验金
							$rule = Rule::find()->where(['title' => '好友注册', 'status' => Rule::STATUS_ACTIVE])->one();
							if ($rule) {
								$rid = $rule->id;
								$r_money = $rule->money;
								$model_gold = new Gold();
								$model_gold->rid = $rid;
								$model_gold->money = $r_money;
								$model_gold->uid = $code_source['id'];
								$model_gold->created_at = strtotime("now");
								$model_gold->save();
							}

						} elseif ($_POST['SignupForm']['invitation_code']) {
							$code_source = UcenterMember::find()->where(['invitation_code' => $_POST["SignupForm"]["invitation_code"]])->asArray()->one();
							if ($code_source) {
								$newModel->invitation_id = $code_source['id'];
							}
							//好友注册领取体验金
							$rule = Rule::find()->where(['title' => '好友注册', 'status' => Rule::STATUS_ACTIVE])->one();
							if ($rule) {
								$rid = $rule->id;
								$r_money = $rule->money;
								$model_gold = new Gold();
								$model_gold->rid = $rid;
								$model_gold->money = $r_money;
								$model_gold->uid = $code_source['id'];
								$model_gold->created_at = strtotime("now");
								$model_gold->save();
							}
						}
						$newModel->save(false);

						$model_asset_info = new Info();
						$model_asset_info->member_id = yii::$app->user->id;
						$model_asset_info->create_at = strtotime("now");
						$model_asset_info->save(false);
						//手机号注册领取体验金
						$rule = Rule::find()->where(['title' => '手机号注册', 'status' => Rule::STATUS_ACTIVE])->one();
						if ($rule) {
							$rid = $rule->id;
							$r_money = $rule->money;
							$model_gold = new Gold();
							$model_gold->rid = $rid;
							$model_gold->money = $r_money;
							$model_gold->uid = $code_source['id'];
							$model_gold->created_at = strtotime("now");
							$model_gold->save();
						}

						return $this->goHome();
					}
				}
			}
		}

		return $this->render('signup', compact("model", "code"));
	}
    //用户签到
    public function actionCheckin()
    {
    	if(!yii::$app->user->isGuest)
    	{
    		$uid = yii::$app->user->id;
    		//$invest_total = \common\models\fund\Order::find()->where(['member_id'=>$uid,'status'=>1])->sum('money');
    		
    				$checkin_total = count(SignIn::find()->where('sign_in_time >='.strtotime(date("Y-m-d")))->all());//本日签到总人数
    			
    				/* $model = new RaiseCard();
    				$model->member_id = Yii::$app->user->id;
    				$model->create_at = strtotime("now");
    				$coupon_id = Setting::find()->where(['code'=>'checkrate'])->asArray()->one()['value'];
    				$model_activity_card = Card::find()->where('id='.$coupon_id)->asArray()->one();
    				$model->coupon_id = $coupon_id;
    				$model->status = 0;
    				$model->rate = $model_activity_card['rate'];
    				$model->validity_start_at = $model_activity_card['use_start_at'];
    				$model->validity_out_at = $model_activity_card['use_out_at'];
    				$model->save(false); */
    				try 
    				{
    					$info = \frontend\actions\app\member::signIn(yii::$app->user->id, '1');
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
    
    //立即投资
    public function actionInvestinfos()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}
    	$pid = $_GET['id'];
    	$uid = yii::$app->user->id;
    	$model = Product::find()->where('id='.$pid)->one();
    	$model_asset = Info::find()->where(['member_id'=>Yii::$app->user->id])->one();
    	$model_orders = Order::find()->where('product_id='.$pid)->asArray()->all();
    	//本期投资记录
    	$query = (new Query())
    	->select("C.real_name,A.start_money,A.start_at")
    	->from("fund_orders AS A")
    	->where("product_id=".$pid)
    	->join("left join", "ucenter_member AS C","A.member_id = C.id")->orderBy('start_at DESC');
    	
    	$rows = $query;
    	$pages = new Pagination(['totalCount' => $rows->count(), 'pageSize' => '8']);
    	$array_investlog = $rows->offset($pages->offset)
    	->limit($pages->limit)
    	->all();
    	//最大投资限额——（后台设置里取值）
    	$limitConfig = sinapay::getsiteConfig();
    	$invest_max = 10000;
    	$invest_min = 5;
    	$invest_times = 3;
    	if($limitConfig)
    	{
    		$invest_max = $limitConfig->invest_max;
    		$invest_min = $limitConfig->invest_min;
    		$invest_times = $limitConfig->invest_num;
    	
    		//当日投资次数
    		$today_num = Log::find()->where('member_id = '. $uid .'  AND create_at > '. strtotime(date("Y-m-d")) .' AND status=2')->count();
    	
    	}
    	$k_money = Invest::kmoney($pid);
    	return $this->render('investinfos',compact("invest_max","invest_min","invest_times","today_num","model","k_money","model_orders","array_investlog","pages","model_asset"));    	 
    }
    
    //购买
    public function actionBuy()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}
    	$isAuthentic = member::isAuthentic(yii::$app->user->id);
    	if(!$isAuthentic)
    	{
    		header("Content-type: text/html; charset=utf-8");
    		echo "<script>alert('您还没有实名制认证')</script>";
    		echo "<script>window.location.href='".\yii\helpers\Url::to(['setting/setting'])."'</script>";
    		exit;
    	}
    	$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
    	if(!isset($model_asset))
    	{
    		//转到绑定银行卡页面
    		header("Content-type: text/html; charset=utf-8");
    		echo "<script>alert('请先绑定银行卡')</script>";
    		echo "<script>window.location.href='".\yii\helpers\Url::to(['money/bindcard'])."'</script>";
    		exit;
    	}
    	
    	if(isset($_POST['pid']) && isset($_POST['money']))
    	{
    		$uid = Yii::$app->user->id;
    		$pid = $_POST['pid'];
    		$money = $_POST['money'];
    		try
    		{
    			$result = Invest::invest($uid, $pid, $money);
    			if($result)
    			{
    				Invest::gold($uid,$money);
    				echo '购买成功';
    				exit;
    			}
    		}
    		catch (ErrorException $e)
    		{
    			echo $e->getMessage();
    			exit;
    		}
    	}
    	return $this->render('buy');
    	 
    }
    
    
    
    //提现
    public function actionWithdraw()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}
    	$uid = Yii::$app->user->id;
    	$money = $_POST['money'];
    	try 
    	{
    	  $result = Withdrawals::withdraw($uid, $money);
    	  if($result)
    	  {
	    	  echo '提现成功';
	    	  exit;
    	  }
    	}
    	catch (ErrorException $e)
    	{
    		echo $e->getMessage();
    		exit;
    	}
    	
    	return $this->render('recharge', compact("bank_card","balance"));
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
    	//修改密码
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
    	//实名验证
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
    			if(is_bool($result))
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
    	//设置头像
    	if(isset($_POST['UcenterMember']['person_face']))
    	{
	    	$model->person_face = UploadedFile::getInstance($model, 'person_face');
	    	if ($model->person_face) 
	    	{
	            
	            if ($model->validate()) {
	            	$Name = mt_rand(1100,9900) .time() .'.'. $model->person_face->extension;
	            	$model->person_face->saveAs('../web/upload/'.$Name);
	            	//保存头像名到数据表
	            	$model->person_face = $Name;
	            	if($model->save(false))
	            	{
	            		echo '<script>alert("头像修改完成~");</script>';
	            	}
	            	
	            }
	        }
    	} 
    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('setting',compact("infos_rar","model","model_password","is_Authentic"));
    }
    
    //我的礼券
    public function actionGift()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}
    	$infos_rar = $this->Ucenter();	//用户数据包
    	
    	$uid = yii::$app->user->id;
    	//未使用的礼券
    	$gift_Notuse_Query = (new Query())
    	->select("A.rate,A.validity_start_at,A.validity_out_at,B.title")
    	->from("activity_raise_card AS A")
    	->where("status=0 AND member_id=".$uid)
    	->join("left join","activity_card AS B","A.coupon_id=B.id");
    	
    	$pages_Notuse = new Pagination(['totalCount' => $gift_Notuse_Query->count(), 'pageSize' => '2']);
    	$gift_Notuse_Infos = $gift_Notuse_Query->offset($pages_Notuse->offset)
    	->limit($pages_Notuse->limit)
    	->all();
    	//已使用的礼券
    	$gift_Used_Query = (new Query())
    	->select("A.rate,A.validity_start_at,A.validity_out_at,B.title")
    	->from("activity_raise_card AS A")
    	->where("status=1 AND member_id=".$uid)
    	->join("left join","activity_card AS B","A.coupon_id=B.id");
    	
    	$pages_Used = new Pagination(['totalCount' => $gift_Used_Query->count(), 'pageSize' => '2']);
    	$gift_Used_Infos = $gift_Used_Query->offset($pages_Used->offset)
    	->limit($pages_Used->limit)
    	->all();
    	//已过期的礼券
    	$gift_Expire_Query = (new Query())
    	->select("A.rate,A.validity_start_at,A.validity_out_at,B.title")
    	->from("activity_raise_card AS A")
    	->where("status=2 AND member_id=".$uid)
    	->join("left join","activity_card AS B","A.coupon_id=B.id");
    	 
    	$pages_Expire = new Pagination(['totalCount' => $gift_Expire_Query->count(), 'pageSize' => '2']);
    	$gift_Expire_Infos = $gift_Expire_Query->offset($pages_Expire->offset)
    	->limit($pages_Expire->limit)
    	->all();
    	
    	return $this->render('gift',compact("infos_rar","gift_Notuse_Infos","gift_Used_Infos","gift_Expire_Infos","pages_Notuse","pages_Used","pages_Expire"));
    }
    
    //邀请注册
    public function actionInvitation()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}
    	$uid = yii::$app->user->id;
    	$invitation_code = UcenterMember::findIdentity($uid)->invitation_code;
    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('invitation',compact("infos_rar","invitation_code"));
    }
    
    //法律服务
    public function actionLaw()
    {
    	if(\yii::$app->user->isGuest)
    	{
    		$this->redirect(['site/login']);
    	}

    	$infos_rar = $this->Ucenter();	//用户数据包
    	return $this->render('law',compact("infos_rar"));
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public static function  Ucenter()
    {
    	$model = UcenterMember::find()->where('id='.Yii::$app->user->id)->one();
    	//个人账户
    	$model_asset = Info::find()->where('member_id='.Yii::$app->user->id)->one();
    	
    	$updated_at = $model->updated_at;
    	$balance = $model_asset->balance;
    	
    	$username = yii::$app->user->identity->username;
    	return compact('email','balance','username');
    }
    public function actionXieyi(){
        return $this->render('xieyi');
    }
    
    //根据IP获取所在地区
    public static function get_area($ip)
    {
    
    	//        $ip = '120.3.255.92';
    	$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    	if (empty($res)) {
    		throw new \ErrorException('获取注册地区失败', 1010);
    	}
    	$jsonMatches = array();
    	preg_match('#\{.+?\}#', $res, $jsonMatches);
    	if (!isset($jsonMatches[0])) {
    		throw new ErrorException('获取注册地区失败', 1010);
    	}
    	$json = json_decode($jsonMatches[0], true);
    	if (isset($json['ret']) && $json['ret'] == 1) {
    		$json['ip'] = $ip;
    		unset($json['ret']);
    	} else {
    		throw new ErrorException('获取注册地区失败', 1010);
    	}
    	$area = $json['country'] . '-' . $json['province'] . '-' . $json['city'];
    	return $area;
    }
}
