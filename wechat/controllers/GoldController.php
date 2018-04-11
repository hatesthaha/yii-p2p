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
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\base\fund\Income;
use framework\helpers\Jssdk;
use frontend\actions\App\AloneMethod;

/**
 * Site controller
 */
class GoldController extends FrontendController
{
    public function actions() {
        return [
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }
    public function actionGindex()
    {
        Utils::ensureOpenId();
        $openId = Yii::$app->request->get('open_id');
        if(($usermodel = UcenterMember::findOne(['openid' => $openId])) !== null){
            Yii::$app->user->login($usermodel);
        }elseif(empty(Yii::$app->user->id)){
            return $this->redirect(['site/signin']);
        }
        $uid = yii::$app->user->id;
        $gold = Gold::find()->select('uid,sum(money) as smoney')->where(['uid'=>$uid])->asArray()->one();
        $allgold = Gold::find()->select('uid,rid,sum(money) as smoney')->where(['uid'=>$uid])->asArray()->groupBy('rid')->all();
        $invitation_code = UcenterMember::findIdentity($uid)->invitation_code;
        $goldincome = Income::find()->select('member_id,sum(goldincome) as sincome')->where(['member_id'=>$uid])->asArray()->one();
        $jssdk = new Jssdk('wx32814d588c44c17c','23b0f218574551f18db1dc991dbee87f');
        $signpack = $jssdk->getSignPackage();

        return $this->view('gindex',compact('gold','allgold','goldincome','invitation_code','signpack'));
    }
    public function actionGshare()
    {
        return $this->view('gshare');
    }
    public function actionGsignup()
    {
        if($_GET['code']){
            $code = $_GET['code'];
        }
        if($_POST){
            $member = member::register($_POST['phone'],$_POST['password'],$_POST['password'],$_POST['code'],'4',$_POST['url_code']);
            if($member['errorNum']==1){
                return $this->goBack([
                    'info' => $member['errorMsg'],
                ], Url::to(['gsignup']));
            }else{
                return $this->redirect('site/member');
            }
        }
        return $this->view('gsignup',compact('code'));
    }
    
	//体验金列表
    public function actionExperiencelist()
    {
    	$uid = yii::$app->user->id;
    	$result = AloneMethod::experience_gold_log($uid,1,15);
    	$list = $result[data]['list'];
    	$count = $result[data][total_count];
    	$list_pages = ceil($count/15);
    	return $this->view('experiencelist',compact('list','list_pages'));
    }
    //体验金列表加载数据
    public function actionExperiencedatas()
    {
    	if($_REQUEST)
    	{
    		$uid = yii::$app->user->id;
    		$page = intval($_REQUEST[page]);
    		$page_num = intval($_REQUEST[num]);
    		try
    		{
    			$result = AloneMethod::experience_gold_log($uid,$page, $page_num);
    		}
    		catch(Exception $ex)
    		{
    			$result ='';
    		}
    		$datas = json_encode($result[data]['list']);
    		echo $datas;
    	}
    }
    //推荐红包列表
    public function actionRecommendlist()
    {
    	$uid = yii::$app->user->id;
    	$result = member::get_rad_list($uid,1,15);
    	$list = $result[data]['list'];
    	$count = $result[data][count];
    	$list_pages = ceil($count/15);
    	$sum = $result[data]['sum'];
    	return $this->view('recommendlist',compact('list','list_pages','sum'));
    }
    //体验金列表加载数据
    public function actionRecommenddatas()
    {
    	if($_REQUEST)
    	{
    		$uid = yii::$app->user->id;
    		$page = intval($_REQUEST[page]);
    		$page_num = intval($_REQUEST[num]);
    		try
    		{
    			$result = member::get_rad_list($uid, $page, $page_num);
    		}
    		catch(Exception $ex)
    		{
    			$result ='';
    		}
    		$datas = json_encode($result[data]['list']);
    		echo $datas;
    	}
    }
}




