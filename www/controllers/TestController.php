<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/31
 * Time: 11:21
 */

namespace www\controllers;

use common\models\base\activity\ActivityLog;
use common\models\base\activity\HoldActivity;
use common\models\base\activity\VirtualProduct;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\cms\Article;
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\base\site\VerifyCode;
use common\models\sinapay\SinaNotifyTrade;
use common\models\UcenterMember;
use framework\lianlian\lianlianClass;
use framework\sinapay\Weibopay;
use frontend\actions\AloneMethod;
use frontend\actions\App;
use frontend\actions\Invest;
use frontend\actions\lianlian;
use frontend\actions\sina;
use frontend\actions\sinapay;
use frontend\actions\Withdrawals;
use Yii;
use frontend\actions\app\Balance;
use frontend\actions\app\member;
use frontend\actions\app\Port;
use frontend\actions\app\yeepay;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\User;

class TestController extends Controller{
    public function actionWly(){
        $test = sinapay::bindingBankCard('118','201510285310056139580','15231231883');
//		$test = Balance::isBinding('11');
		var_dump($test);exit;
//		$test = Balance::bindbankcard(31,'6217000140004808851','131102199007042019','王利亚','15127281324','1');
//		$test = Balance::bindBankcardConfirm(31,'88782b223a34b136e068380d4a7a67ce75209','544755');

//		$test = Balance::setBalance('11',1);
//		$test = Balance::confirmSet('201507244850494903618','434819');
//		var_dump($test);

//		短信验证 发送短信--验证手机
//		$test = Port::ValidatePhone('15127281324');
//		$test = Port::checkPhnoe('15127281324','775527');
//		实名认证 身份证与姓名对应
//		$test = member::authentication('52','王利亚','131102199007042019');

//绑卡--》绑卡确认--(查询)》绑卡后支付--》支付确认--（查询）》订单查询---》提现操作
        //绑卡操作 输入信息---进行确认
//      $test = yeepay::bindbankcard(12,'6217000140004808851','131102199007042019','王利亚','15127281324',1);
//		$test = yeepay::bindBankcardConfirm('4b19627eba749b32d1ec46348ce347aa27322','068268');
//		查询绑卡信息
//		$test = yeepay::bankcardList('0276867a089e23c4201d0ddd7a62fa8b88403');

        //用户充值  充值金额--短信验证
//      $test = yeepay::payment(11,1);
//		$test = yeepay::confirmPayment('201507239952989849677','430672');
        //查询支付订单信息
//		$test = yeepay::paymentQuery('201507225453102169489','411507233860836495');
//$test = Balance::isBinding('11');
//		用户提现
//		$test = yeepay::withdraw(11,10);
        //订单查询
//		$test = yeepay::payClearData('2015-07-01','2015-07-23');



//		$test = Balance::bindbankcard('1','6217000140004808851','131102199007042019','王利亚','15127281324',1);
//		$test = Balance::bindBankcardConfirm('1','3561ae0fc7db3bc677bb7672af27c46703124','105618');
//		var_dump(Yii::$app->request->post());

        //app
        //是否实名验证
//        $test = member::isAuthentic('1');
        //绑卡操作
//        $test = Balance::bindbankcard(1,'6217000140004808851','15127281324',1);
//        $test = Balance::bindBankcardConfirm(1,'61256eedde9796991433e599ba7b2cd046813','018850');
        //支付操作
//        $test = Balance::setBalance(1,0.01);
//        $test = Balance::confirmSet('201507319749100523334','109815');
        //获取绑卡信息
//          $test = Balance::getBalance(1);
//        $test = yeepay::withdraw(1,1);

        //        $test = Port::ValidatePhone('15127281324');
//        var_dump($test);
//        $test = User::getIdentity();
//        var_dump($test);
//        $test = \frontend\actions\member::phoneIsRegister('15127281324');
//        $test = \frontend\actions\Port::ValidatePhone('15127281324');
//        $test = member::register('8888811','123','123','1','1','1');
//        var_dump($test);

//         $test = Yii::$app->user->renewAuthStatus();
//        $session = Yii::$app->getSession();
//        $id = $session->getHasSessionId();
//        $request = Yii::$app->getRequest();
//        $test = member::phoneRegister('15127281324');
//        $test = member::random();
//        $test =VerifyCode::find()->where([
//            'field' => '15127281324',
//            'status' => -1
//        ])->orderBy('b_time desc')->one();
//        $test = \frontend\actions\member::getInvite('1');
//        $test = \frontend\actions\member::authentication(24,'王利亚','131102199007042019');
//          $test = member::phoneRegister('18518674993');
//        $test = Port::ValidatePhone('15127281324');
//        $test = Port::checkPhnoe('15127281324','079361');
//        $test = member::register('18518674993','123456','123456','1','932513');
//        $test = member::login('18518674993','123456');
//        $test = member::logout(37);
//        $test = member::isAuthentic(22);
//        $test = \frontend\actions\Balance::bindbankcard2(27,'123456','11111');
//        $test = \frontend\actions\Balance::setBalance2(27,100);
//        $test = \frontend\actions\Balance::bindbankcard(22,'6217000140004808851','131102199007042019','王利亚','15127281324','1');


        //连连支付接口---参数uid，银行卡号，充值金额。---自动判定用户是否实名认证，非实名认证不能进行
//          $test = lianlian::confirmation(24,'6217000140004808851','0.01');
//        $test = lianlian::confirmation(25,'6227000140510442244','0.01');
//        $test = \frontend\actions\member::getInvite('24');

        ////新浪支付
      //    $sina = new sina();
//          $test = $sina->query_verify('HQW131102199007042019');
//        $lili = new lianlianClass();
//          $test = $sina->create_activate_member(time());
//        $test = $sina->set_real_name('20150819100838','王','131102199007042000');
//        $test = $sina->binding_verify();
//        $test = $sina->unbinding_verify();
//        $test = $sina->query_verify();
//        $test = $lili->bankcardQuery('6217000140004808851');
//        var_dump($test);

//        $test = lianlian::getBankcard(1);

          //使用连连支付其他接口
//        $ll = new lianlianClass();
        //订单查询接口 参数--商户订单号，订单时间，连连返回订单号
//        $test = $ll->orderQuery('cc522b525ff348b9e58456c829e2e44a','20150808100430','2015080811466969');
        //银行卡查询接口
//        $test = $ll->bankcardQuery('6226901805086869');
        // 用户在连连支付情况
//        $test = $ll->userBankcard('d0bf2c2b6f14b7b1a10a5c3d797b4ba4');
//        $test = $ll->bankcardunbind('3fef8327bc176698230248dbc1222579','2015080818510075');
//        $test = \frontend\actions\member::getPhone('24');


            //实名-创建新浪会员
//        $test = sinapay::authentication('88','王利亚','131102199007042019');

//        $test = sinapay::bindingBankCard('96','6217000140004808851','15127281324');
//        $test = sinapay::checktodaydeposit('101');
//        $test = sinapay::bankCardInfo('6225880136674703');
//        $test = sinapay::updatebank('44',100);
//        $test = sinapay::bankCardAdvance('201508201001005397980','bdf307fc78ab438eb5991aa11c87809b','020346');
//        $test = sinapay::queryBankCard('44');

//        $test = sinapay::isBinding('44');
        //用户充值操作
//        $test = sinapay::recharge('44',1000);
//        $test = sinapay::getUserInfo('44');
//        $test = sinapay::checkdeposit('44',49900);

//        $test = sinapay::recharge('44','1');
//        $test = sinapay::recharge('105','100');
//        $test = sinapay::rechargeComfirm('201510125157981071041','5c8c0014fb344b13beaaf97f1dc73b6e','841149');
//$test = $sina->query_hosting_deposit_order('1440052548HQW131102199007042019','201510054810154542450');
//        $test = sinapay::bankCardInfo('6226901805086869');

//        $test = $sina->query_verify('1440032322HQW131102199007042019');
//        $test = $sina->query_balance('1440032322HQW131102199007042019');

        //查询网站用户金额
//        $test = $sina->query_balance('20150831183009HQW610523198304110017'); //查询账户余额 44
//        $test = $sina->query_balance('200004227922','MEMBER_ID',"BASIC"); //查询账户余额 44 938.09 -100
//        $test = sinapay::querySinaBalance('74');//网站余额
//          $test = sinapay::balanceFreeze('44','1','11');
//        $test = $sina->query_balance('1440236114HQW131102199007042019'); //查询账户余额 27
//        $test = $sina->query_balance('1440147796HQW131102199007042019'); //查询账户余额 38 190.09 +30
//        $test = $sina->query_balance('1440144466HQW131102199007042019'); //查询账户余额 42 20



//        $test = $sina->query_hosting_deposit('1440144466HQW131102199007042019','');

//        $test = sinapay::rechargeComfirm('201508219810049981245','dc7a57ba64fa4a2985408bef17026c39','378446');

//        $test = sinapay::invest('44','1','0.01');



//        $test = sinapay::invest('44','10','10'); //用户投资
//        $test = sinapay::invest('44','10','10'); //用户投资
//        $test = sinapay::sitePeyee('38','100','201508221019949924038','1440032322HQW131102199007042019'); //网站收钱
//        $test = sinapay::hostingRefund('1440052548HQW131102199007042019','201508249749555163125',100); //中间账户退款
//        $test = $sina->query_hosting_refund_byorder('1440052548HQW131102199007042019','201508244848571033366');
//          $test = sinapay::withdraw('44','19'); //用户提现 网站通过
//          $test = sinapay::sianWithdraw('1440052548HQW131102199007042019','201508241011005040879');

//        $test = sinapay::sinaRansom('1440052548HQW131102199007042019','201508231015152521185');
//        $test = \frontend\actions\member::authentication('27','网名','131102199007042019');
//        $test = $sina->query_hosting_withdraw_order('1440052548HQW131102199007042019','SAVING_POT','201508221025452508718');
//        $test = $sina->create_hosting_withdraw('111111111111','1440052548HQW131102199007042019','SAVING_POT','10','31193');

//        $test = sinapay::withdraw('44',1);
//        $test = sinapay::sianWithdraw('1440052548HQW131102199007042019','201509075457555186256');
//        $test = sinapay::hostingRefund('1440052548HQW131102199007042019','201508235753565114051',10,'123');
//        $test = $sina->create_single_hosting_pay_trade(time(),'1440032322HQW131102199007042019','SAVING_POT','11','11111');

//        $test = sinapay::query_hosting_withdraw_time('1440052548HQW131102199007042019',time()-72000000,time());
        $pay_uid = array(
            '27' => '1',
            '42' => '2',
            '38' => '3'
        );
//        $test = sinapay::batchPay($pay_uid,'201508235753565114051');
//        $test = sinapay::giveInterest('27','11'); //给利息
//        $test = sinapay::collectSite(4000,array('44'=>'4000'));
//        $test = sinapay::collectUser('44',1000);
//        $test = sinapay::unbinding_bank_card('44');
//        $test = sinapay::sinaRansom('27','11000','44');
//        $test = sinapay::sianWithdrawOnly('44',1);
//            $test = sinapay::getBankCard('44');
//        $test = \frontend\actions\Port::sendSms2('15127281324','123456','3','4');

//        $test = $sina->query_bank_card('1440817121HQW370105198910176257','32694');
//        $test = sinapay::getUserInfo('44');
//        $test = $sina->getConfig();
//        $wei = new Weibopay();
//        $test = $wei->getConfig();
//        $test = sinapay::getConfig();
//$test = \frontend\actions\Port::ValidatePhone('15127281324');
//        $test = member::signIn('111','2');
//        $test = member::get_yesterday_sign_in();
//        $test = member::get_today_sign_in();
//        $test = member::get_user_sign_in(1);
//        $test = member::is_sign_today('21');
//        $test = member::get_yesterday_user('1');
        //$test = $sina->create_activate_member(time());
//        $test = Withdrawals::User_redeem('53','1000');
        //$test = Invest::invest('53','16','1100');
//        $test = $sina->set_real_name('20150902161534HQW131102199007042019','伍文瀚','130603198803230318');
      //  $test = member::get_user_sign_in('1','4','2');
//        $test = \frontend\actions\App\Invest::product_list(4,1);

//        $test = member::phonpreg_matchster('18518674993');
//        $test = member::set_invitation(5);
//        $test = member::verify_code('57565356');
//        $test = member::use_code('1');
//        $test = member::register('18518674993','123456','123456','222222','3','48529853');
//        $test = App::sessionkey_istimeout('f91f250602f33523296fa93d9ea086e447929--3');
//        $test = sinapay::recharge('44',11.5);
//        $test = member::get_invest_num('44');
//        $test = sinapay::sianWithdrawOnly('44',1);
//        $test = sinapay::getUserInfo('44');
//        $test = member::set_invitation();
//        $test = App\Invest::product_list('1','2');
//        $test = sinapay::bankCardInfo('6226320130074914');
//        $test = member::give_experience_gold('手机号注册','12');
//        $test = \frontend\actions\Port::ValidatePhone('15127281324');
        //$test = \frontend\actions\Port::checkPhnoe('15127281324','095130');
//        $test = sinapay::collectSite(4000,array('38'=>'4000'));
//        $test = App\AloneMethod::invest_log('44','5','1');
//        $test = sinapay::authentication('79','王利亚','131102199007042019');
//        $test = sinapay::getUserInfo(44);
//        $test = member::signIn('44','3');
//        $test = sinapay::test('55','100');
//        $test = sinapay::immediate_withdraw('44','200');
//        $test = App\AloneMethod::reading_log('11','12');
//        $test = App\AloneMethod::is_reading('12','12');
//        $test = App\AloneMethod::is_msg(array('33','55','44','66'),'12');
//        $test =  App\AloneMethod::ignore_all(array('33','55','44','66'),'12');
//        $test = App\AloneMethod::is_exit_msg('44');
//        $test = App\AloneMethod::user_msg_centor('44','1','4');
//        $test = sinapay::sianWithdrawOnly('44','10');
//        $test = sinapay::get_sina_balance_all('55');
//        $test = sinapay::get_deal();
//        $test = App\AloneMethod::experience_gold_log('44');
//        $test = App\AloneMethod::cms_lunbo();
//        $test = App\AloneMethod::encrypt('15127281324');
//        $test = App\AloneMethod::decrypt('lmZhk5uVa2pna5Y=');
//        $test = member::active_log('15127281324','22','18518674993',111,90,'中秋节');
//        $test = member::get_invite_info('YW5lZ5lmnWaabWg=');
//        $test = $sina->create_hosting_deposit();
//        $test = sinapay::getUserInfo('44');
//        $test = member::get_invite_info('22');
//        $test = member::create_read_packet('15127281324',10);
//        $test = member::update_red_packet('94','15127281324','18518674992','国庆节活动');

//        $cookies = Yii::$app->response->cookies;
//        $cookies->add(new \yii\web\Cookie([
//            'name' => 'language',
//            'value' => 'zh-CN',
//        ]));
//
//        var_dump($cookies['language']->value);
//      $test = member::get_red_packet('15127281324');
//        $test = member::get_rad_list('44');
//        $test = member::get_user_red_packet('44');
//        $test = member::draw_red_packet('44',time());
//        //活动期间利率调整TODO
//        date_default_timezone_set('PRC');
//        //活动开始时间
//        $begin_time = strtotime('2015-9-24');
//        $test = UcenterMember::find()->asArray()->all();
       //     获取所有用户信息

//        echo '<hr/>';
//        foreach($users as $key => $value){
//            var_dump($value['id']);
//        }
//        if($users){
//            foreach($users as $key=>$value){
//                $flag = Gold::find()->where(['uid' => $value['id'],'rid' => '3','money' =>'8150'])->one();
//                if(!$flag){
//                    $rule = Rule::find()->where(['id' => '3', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
//                    //判断规则是否生效
//                    if ($rule['time']) {
//                        $rul_money = $rule['money'];
//                        $end_at = time() + $rule['time'] * 24 * 3600;
//                        //增加体验金
//                        $gold = new Gold();
//                        $gold->uid = $value['id'];
//                        $gold->rid = '3';
//                        $gold->money = $rul_money;
//                        $gold->end_at = $end_at;
//                       echo  $gold = $gold->save();
//                    }
//                }
//
//            }
//        }

//        $test = App\AloneMethod::send_msg_all();
//        $test = Port::ValidatePhone2('15127281324','1');
//        $test = Port::checkPhnoe('15127281324','118812');
//        $test = ArrayHelper::map()
//        $test = Port::ValidatePhone2('15127281324','2','100');

//        $prize_arr = array(
//            '0' => array('id'=>1,'prize'=>'5','v'=>1),
//            '1' => array('id'=>2,'prize'=>'4.5','v'=>5),
//            '2' => array('id'=>3,'prize'=>'4','v'=>10),
//            '3' => array('id'=>4,'prize'=>'3.5','v'=>20),
//            '4' => array('id'=>5,'prize'=>'3','v'=>30),
//            '5' => array('id'=>6,'prize'=>'2.5','v'=>40),
//            '6' => array('id'=>7,'prize'=>'2','v'=>50),
//            '7' => array('id'=>8,'prize'=>'1.5','v'=>60),
//            '8' => array('id'=>9,'prize'=>'1','v'=>70),
//            '9' => array('id'=>10,'prize'=>'0.5','v'=>100),
//        );
//        //读取奖项设定
//        $prize = array();
//        $test = HoldActivity::find()->where(['id' => 7])->asArray()->one();
//        $radearray = explode('/',$test['red_money_rang']);
//        if(count($radearray)){
//            foreach($radearray as $key=>$value){
//                $v1 = explode(',',$value);
//                if(count($v1)){
//                    $v2 = explode('-',$v1['0']);
//                    $money = rand($v2['0']*100,$v2['1']*100)/100;
//                    $prize[$key] = array('id' => $key+1,'prize' => $money,'v' => $v1['1']);
//
//                }
//            }
//        }
//        $ridearray = explode(',',$test['rid_list']);
//      echo in_array('3',$ridearray);

//        $test = member::create_read_packet('15127281324',10,'11111');
//        $test = App\Invest::goldtwo('94',10);
//        $test = member::get_user_red_packet('94');

        ////
//        $test = AloneMethod::get_continue_money('44',100,15);
//        $test = AloneMethod::get_relation('96');
//        $test = AloneMethod::produce_red_packet('96');
//        $test = AloneMethod::send_red_packet('96');
//        $test = AloneMethod::get_recommend_relation('96',1,4);
//        $test = AloneMethod::produce_recommend('103');
//        $test = member::draw_red_packet('100',time());
//        $test = member::get_user_red_packet('100');
//        $test = member::get_deposit_num('66');
//        $test = member::give_experience_gold(4,10);
//        $test = sinapay::immediate_withdraw(105,10);
//        $test = member::phoneIsRegister(15127281324);
//        $test = member::the_master_register('18518674990','w123456','344274','105');
//        $test = App\AloneMethod::decrypt('15127281324');
//        $test = sinapay::bankCardInfo('622600910075787620');
//        $test = AloneMethod::produce_red_packet('106')
//            $test = App\Withdrawals::Redeem('105',1);
//        $test = member::get_rad_list('106');
//        $test = sinapay::getUserInfo('106');
//        $test = member::draw_red_packet('106', time());
//        $test = 76.76;
//        $cur = 60.76;
//        $test = ($test * 100 - $cur*100)/100;
//        var_dump($test);
//        $cur = 16;
//
//        var_dump($test * 100 - $cur*100);
//        $test = ($test * 100 - $cur*100)/100;
//        var_dump($test);
//        $test = Article::find()->where(['title' => '法律保障','status' => 1])->asArray()->one();
//        $test = AloneMethod::get_recommend_relation('105');
//        $test = App\Withdrawals::User_redeem('106',9.99);
//        $test = AloneMethod::get_category_article('安全保障');
//            $test = member::get_rad_list('106');
        ////短息接口
//        $test = Port::ValidatePhone2('15127281324','1');//用户注册
//        $test = Port::ValidatePhone2('18518674993','2',9999);//投资操作--慢
//        $test = Port::ValidatePhone2('18518674993','3');//提现操作
//        $test = Port::ValidatePhone2('18518674993','4');//重置密码
//        $test = Port::ValidatePhone2('18518674993','5');//修改密码
//        echo date('y-m-d:H-i-s');
//        $test = AloneMethod::statistics();
//        var_dump($test);
//        $money_sina = sprintf("%.2f",99.0012);
//        var_dump($money_sina);
//        $virtual = new VirtualProduct();
//        $virtual->money = 1;
//        $virtual->name = '网';
//        $virtual->pid = 1;
//       return  $virtual->save();
//        $testName = array(
//            '伟','芳','娜','敏','静','秀英','丽','强','磊','洋','艳','勇','军','杰','娟','涛','超','明','霞','秀兰','刚','平','燕','辉',
//            '玲','桂英','丹','萍','鹏','华','红','玉兰','飞','桂兰','英','梅','鑫','波','斌','莉','宇','浩','凯','秀珍','健','俊','帆',
//            '雪','帅','慧','旭','宁','婷','玉梅','龙','林','玉珍','凤英','晶','欢','玉英','颖','红梅','佳','倩','阳','建华','亮','成',
//            '琴','兰英','畅','建','云','洁','峰','建国','建军','柳','淑珍','春梅','海燕','晨','冬梅','秀荣','瑞','桂珍','莹','秀云','桂荣',
//            '志强','秀梅','丽娟','婷婷','玉华','兵','雷','东','琳','雪梅','淑兰','丽丽','玉','秀芳','欣','淑英','桂芳','博','丽华','丹丹',
//            '彬','桂香','坤','想','淑华','荣','秀华','桂芝','岩','杨','小红','金凤','文','利','楠','红霞','建平','瑜','桂花','璐','凤兰'
//        );
//        $testphone = array(
//            134, 135, 136, 137, 138, 139, 147, 150, 151, 152, 157, 158, 159, 182, 187, 188, // china mobile
//            130, 131, 132, 145, 155, 156, 185, 186, 145, // china unicom
//            133 , 153 , 180 , 181 , 189, // chinatelecom
//        );
//        $chars = "0123456789";
//        $str = '';
//        for ($i = 0; $i < 4; $i++) {
//            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
//        }
//        $length = count($testphone);
//        $key = mt_rand(0,$length-1);
//        return $testphone[$key].'****'.$str;
//        $length = count($testName);
//        echo $length;
//        $key = rand(0,$length-1);
//        var_dump($testName[131]);
//        var_dump('20150902164057HQW130733198702201538');
//        $test = App\Invest::product_list('4','1');
        $test = member::the_promotion_register('15127281324','12345t','123456','2');
        var_dump($test);
    }
    public function actionReturnurl(){
        $ll = new lianlianClass();
        $test = $ll->urlReturn();
        var_dump($test);
    }
    public function actionNotify(){
        $ll = new lianlianClass();
        $test = $ll->returnNotify();
        var_dump($test);
    }
    public function actionLearn(){
        $data = [
            ['age' => 30, 'name' => 'Alexander'],
            ['age' => 30, 'name' => 'Brian'],
            ['age' => 19, 'name' => 'Barney'],
        ];
        ArrayHelper::multisort($data, ['age', 'name'], [SORT_ASC, SORT_DESC]);
        var_dump($data);
    }


}