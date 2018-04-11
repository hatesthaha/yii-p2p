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
use common\models\cms\Article;
use common\models\cms\Category;
use yii\base\ErrorException;
use frontend\actions\Balance;
use common\models\base\asset\Info;
use common\models\base\site\VerifyCode;
use common\models\base\fund\Income;
use common\models\base\fund\Order;
use common\models\base\experience\Gold;
use frontend\actions\app\Port;
use rpc\controllers\AppapiController;
use frontend\actions\AloneMethod;
use frontend\actions\App\Invest;
use yii\base\Exception;
/**
 * Site controller
 */
class SiteController extends FrontendController
{
    public function actionEnter()
    {
        Utils::ensureOpenId();
        if(!Yii::$app->user->getIsGuest()){
            if (
                ($openId = Yii::$app->request->get('open_id')) !== null
                &&
                ($model = UcenterMember::findOne(['openid' => $openId])) !== null
            ) {
                if (Yii::$app->user->login($model)) {
                    return $this->redirect(Url::to(['member']));
                }
            }
            return $this->redirect(Url::to(['signin','openid'=>$openId]));
        } else {
            return $this->redirect(Url::to(['signin']));
        }
    }

    public function actionIndex()
    {
        return $this->redirect(Url::to(['main']));
    }

    public function actionMain()
    {
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }
        if(!\yii::$app->user->isGuest)
        {
            $income_yesterday = Income::find()->where(['member_id'=>$uid,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->smoney;
            $model_asset = Info::find()->where(['member_id'=>Yii::$app->user->id])->one();
            $balance = $model_asset->balance;       //当前余额
            //在投收益
            $invest_income = $model_asset->profit;
            //昨日年化收益率
            $income_rate = income::find()->where(['member_id'=>$uid,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->rate;
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
        }
        //幻灯片
        try
        {
            $cat_id = Cat::find()->where(['name'=>'幻灯片','status'=>Cat::STATUS_ACTIVE])->one()->id;
            $sliders = Link::find()->where(['cat_id'=>$cat_id,'status'=>Link::STATUS_ACTIVE])->asArray()->all();
        }
        catch (ErrorException $e)
        {
            $sliders = "";
        }
        //$products = Product::find()->limit(4)->orderBy('start_at DESC')->all();
        $products = Invest::product_list(4, 1);
        $products_pages = Product::find()->andwhere(['<>', 'status', Product::STATUS_LOCK])->andWhere(['>', 'create_at', 1441641600])->count();
        $products_pages = ceil($products_pages/4);
        return $this->view('main',compact('products','sliders','balance','invest_income','invest_total','income_yesterday','income_rate','products','products_pages'));
    }
    //动态加载项目数据
    public  function actionProductlist()
        {
                if($_REQUEST)
            {
                $page = intval($_REQUEST[page]);
                $page_num = intval($_REQUEST[num]);
                try
                {
                    $result = Invest::product_list($page_num, $page);
                }
                catch(Exception $ex)
                {
                    $result ='';
                }
                $datas = json_encode($result[data]);
                echo $datas;
            }
        }
    public  function actionDetaile()
    {
        $pid = $_GET['id'];
        $product = Product::find()->where(['id'=>$pid])->one();
        return $this->view('detaile',['product'=>$product]);
    }
    public function actionDosignin()
    {
        if($_POST){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $model = new _LoginForm();
            if($model->login($username,$password) ){

            }
        }
    }
    public function actionSignin()
    {
        return $this->view('signin');
    }

    public function actionForgot(){
        return $this->view('forgot');
    }
    public function actionForgotstep1(){
        $session = Yii::$app->session;
        if($_POST){
            $session->set('phone', $_POST['phone']);
            $session->set('code', $_POST['code']);
        }
        return $this->view('forgotstep1');
    }
    public function actionForgotfinish(){
        if($_POST){
            $real_name = $_POST['real_name'];
            $idcard = $_POST['idcard'];
            $phone = $_POST['phone'];
            //$return = member::resetwechatPassword($session->get('phone'),$_POST['password'],$_POST['repassword'],$session->get('code'));
            $password = $_POST['password'];
            $repeatpassword = $_POST['repassword'];
            $code = $_POST['code'];

            $result = Port::checkPhnoe($phone, $code);
            if($result['errorNum'] ==1)
            {
                return $this->goBack([
                        'info' => $result['errorMsg'],
                ], Url::to(['forgot']));
            }
            else
            {
                 $model = UcenterMember::find()->where(['phone'=>$phone,'real_name'=>$real_name,'idcard'=>$idcard])->one();
            $row = UcenterMember::find()->where(['phone'=>$phone,'real_name'=>$real_name,'idcard'=>$idcard])->count();
            if($row == 1)
            {
                $model->password_hash = yii::$app->security->generatePasswordHash($password);
                $app_pwd = md5(sha1($password).time());
                $model->app_pwd = $app_pwd;
                if($model->save(false))
                {
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('密码修改成功')</script>";
                    return $this->view('signin');
                    exit;
                }
            }
                else
                {
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('密码修改失败，请联系客服！')</script>";
                    echo "<script>window.location.href='".\yii\helpers\Url::to(['forgot'])."'</script>";
                    exit;
                }
            }
        }
    }
    public function actionSignup()
    {
        return $this->view('signup');
    }
    public function actionStep1()
    {
    	if($_POST['phone'] && $_POST['password'] && $_POST['code'] && $_POST['password_repeat'])
    	{
    		if($_POST['url_code'])
    		{
    			$invitation_code = $_POST['url_code'];
    		}
    		else 
    		{
    			$invitation_code = $_POST['invitation_code'];
    		}
    		$member = member::wechatregister($_POST['phone'],$_POST['password'],$_POST['password_repeat'],$_POST['code'],'4',$invitation_code);
    		if($member['errorNum']==1)
    		{
    			return $this->goBack([
    					'info' => $member['errorMsg'],
    			], Url::to(['signup']));
    		}
		 return $this->view('member',compact("model"));
    	}
    	else
    	{
    		return $this->view('signup');
    	}
    }

    public function actionStep2()
    {
    	return $this->view('step2');
    
    }
    
    public function actionReg()
    {
    
    	if($_POST){
    		
    		$realname = $_POST['realname'];
    		$cardno = $_POST['cardno'];
    		$id = Yii::$app->user->id;
    		
    		$res = member::authentication($id,$realname,$cardno);
    		if($res['errorNum']==1){
    			return $this->goBack([
    					'info' => $res['errorMsg'],
    			], Url::to(['step2']));
    		}
			else 
    		{
    			header("Content-type: text/html; charset=utf-8");
    			echo "<script>alert('实名制认证成功！');</script>";
    			echo "<script>location.href='".\yii\helpers\Url::to(['site/member'])."'</script>";
    			exit;
    		}
    
    	}else{
    		return $this->view('member');
    	}
    }
    public function actionMember(){
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null)
        {
            Yii::$app->user->login($usermodel);
        }
         elseif(yii::$app->user->isGuest)
        {
            return $this->redirect(['site/signin']);
        }
        $uid = yii::$app->user->id;
       /* $datas = AloneMethod::user_collect(118);
        var_dump($datas);exit;*/

        $model = UcenterMember::find()->where(['id'=>$uid])->one();
        //个人账户
        $income_yesterday = Income::find()->where(['member_id'=>$uid,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->smoney;
        //体验金收益
        $experience_income = Income::find()->where(['member_id'=>$uid,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->goldincome;
        //红包收益
        $railscard = Income::find()->where(['member_id'=>$uid,'created_at'=>strtotime(date('Y-m-d',time()))])->one()->railscard;
        $model_asset = Info::find()->where(['member_id'=>Yii::$app->user->id])->one();
        $balance = $model_asset->balance;		//当前余额
        $income_total = $model_asset->total_revenue;	 //累计收益
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
        
        // 体验金
        $experience_money = 0;
        $money = Gold::find()->where(['<','created_at',time()])->andWhere(['>','end_at',time()])->andWhere(['uid' => $uid ,'status' => Gold::STATUS_ACTIVE ])->sum('money');
        if($money)
        {
        	$experience_money = sprintf("%.2f", $money);
        }
        
        return $this->view('member',compact("model",'income_yesterday','balance','income_total','invest_total','experience_money','experience_income','railscard'));
    }

    public function actionBindcard(){
        $uid = Yii::$app->user->id;
        $isAuthentic = member::isAuthentic(yii::$app->user->id);
        if($isAuthentic['errorNum'] != 0)
        {
            header("Content-type: text/html; charset=utf-8");
            echo "<script>alert('您还没有实名制认证')</script>";
            echo "<script>window.location.href='".\yii\helpers\Url::to(['site/step2'])."'</script>";
            exit;
        }
        $model = UcenterMember::find()->where(['id'=>$uid])->one();

        return $this->view('bindcard',compact("model"));
    }
    
    
    public function actionDoresbind()
    {
        $uid = yii::$app->user->id;
        $model = UcenterMember::find()->where(['id'=>$uid])->one();
        $bandcard = $_POST['bankcard'];
        $phone = $_POST['phone'];
        if($_POST[card_code])
        {
            $card_code = $_POST[card_code];
            $province = $_POST[province];
            $city = $_POST[city];
            $info = sinapay::bindingBankCard($uid, $bandcard, $phone,$province,$city,$card_code);
            echo json_encode($info);
            exit;
        }
        try
        {
            $info = sinapay::bindingBankCard($uid, $bandcard, $phone);
            echo json_encode($info);
            exit;
        }
        catch (ErrorException $e)
        {
            echo $e->getMessage();
            exit;
        }
        if(isset($_POST['bankcard']) && isset($_POST['phone']))
        {
            $cardno = $_POST['bankcard'];
            $phone = $_POST['phone'];
            $info = Balance::bindbankcard2($uid, $cardno,$phone);
            if($info)
            {
                echo '绑卡成功';

                exit;
            }
        }
    }
    public function actionDobindcard(){
        $uid = yii::$app->user->id;
        $model = UcenterMember::find()->where(['id'=>$uid])->one();

        if($_POST){
            if($_POST['bankcard']  && $_POST['phone'] && $_POST['code'] && isset($_POST['ticket']) && isset($_POST['request_no']))
            {
                $valid_code = $_POST['code'];
                $request_no = $_POST['request_no'];
                $ticket = $_POST['ticket'];
                $info = sinapay::bankCardAdvance($request_no, $ticket, $valid_code);
                if($info['errorNum']==1){
                    return $this->goBack([
                        'info' => $info['errorMsg'],
                        'model' => $model,
                    ], Url::to(['bindcard']));
                }
                return $this->view('member');
            }else{
                return $this->goBack([
                    'info' => '你没有输入完整',
                    'model' => $model,
                ], Url::to(['bindcard']));
            }

//            $bandcard = $_POST['bankcard'];
//            $phone = $_POST['phone'];
//            $return = sinapay::bindingBankCard($uid,$bandcard,$phone);
//            if($return['errorNum']==1){
//                return $this->goBack([
//                    'info' => $return['errorMsg'],
//                    'model' => $model,
//                ], Url::to(['bindcard']));
//            }
//            return $this->view('member');
        }
    }
    public function actionSafety()
    {
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }
        return $this->view('safety');
    }
    public function actionAbout(){
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }
        return $this->view('about');
    }
    public function actionGold(){
        $uid = yii::$app->user->id;
        $invitation_code = UcenterMember::findIdentity($uid)->invitation_code;

        return $this->view('gold',compact('invitation_code'));
    }

    public function actionContact(){
        return $this->view('contact');
    }
    public function actionHelp()
    {
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }
        $parent_id = Category::findOne(['title'=>'帮助中心','status'=>1])->id;
        $infos = Article::find()->where(['category_id'=>$parent_id,'status'=>1])->all();
        return $this->view('help',compact('infos'));
    }
    //退出操作
    public function actionLogout()
    {
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
        return $this->redirect(['site/main']);
    }
   
//推广大师
    public function actionTuiguang()
    {
        return $this->view('tuiguang');
    }

    //收益记录
    public function actionIncomelog()
        {
			$uid = yii::$app->user->id;
			//收益记录
			try
			{
				$incomelog = AloneMethod::profit_log($uid, 1, 15);
			}
			catch(ErrorException $ex)
			{
				$incomelog ='';
			}
			$incomelog_pages = Income::find()->select(['smoney','created_at'])->where(['member_id'=>$uid])->asArray()->all();
			$incomelog_pages = ceil(count($incomelog_pages)/15);
            return $this->view('incomelog',compact('incomelog','incomelog_pages'));
        }
	//收益记录慢加载数据
    public function actionIncomelist()
	{
        if($_REQUEST)
        {
			$uid = yii::$app->user->id;
            $page = intval($_REQUEST[page]);
            $page_num = intval($_REQUEST[num]);
			try
			{
				$result = AloneMethod::profit_log($uid, $page, $page_num);
			}
			catch(ErrorException $ex)
			{
				$result ='';
			}
            $datas = json_encode($result);
			echo $datas;
        }
	}
}