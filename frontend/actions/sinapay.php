<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/8/19
 * Time: 9:58
 */

namespace frontend\actions;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\experience\Gold;
use common\models\base\fund\Income;
use common\models\base\setting\BankList;
use common\models\invation\AssetConfig;
use common\models\sinapay\SinaBank;
use common\models\sinapay\SinaBatchpay;
use common\models\sinapay\SinaConfig;
use common\models\sinapay\SinaDeposit;
use common\models\sinapay\SinaFreeze;
use common\models\sinapay\SinaInvest;
use common\models\sinapay\SinaMember;
use common\models\sinapay\SinaNotify;
use common\models\sinapay\SinaNotifyBatchPay2bank;
use common\models\sinapay\SinaNotifyBatchTrade;
use common\models\sinapay\SinaNotifyDeposit;
use common\models\sinapay\SinaNotifyRefund;
use common\models\sinapay\SinaNotifyTrade;
use common\models\sinapay\SinaNotifyWithdraw;
use common\models\sinapay\SinaRansom;
use common\models\sinapay\SinaWithdraw;
use common\models\sinapay\SinaWithdrawTwo;
use common\models\sinapay\SiteSinaBalance;
use common\models\UcenterMember;
use framework\sinapay\Weibopay;
use frontend\actions\App\AloneMethod;
use frontend\actions\app\member;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class sinapay extends Component{
    private $sina_config = array();

    public function __construct() {
        //用户配置--获取数据库内容
        $sina_config = self::getConfig();
        $config = array(
            'sinapay_version' => trim($sina_config->sinapay_version),
            'sinapay_partner_id' => trim($sina_config->sinapay_partner_id),
            'sign_type' => trim($sina_config->sign_type),
            'sinapay_md5_key' => trim($sina_config->sinapay_md5_key),
            'sinapay_input_charset' => trim($sina_config->sinapay_input_charset),
            'sinapay_rsa_sign_private_key' => trim($sina_config->sinapay_rsa_sign_private_key),
            'sinapay_rsa_sign_public_key' => trim($sina_config->sinapay_rsa_sign_public_key),
            'sinapay_rsa_public__key' => trim($sina_config->sinapay_rsa_public__key),
            'sinapay_mgs_url' => trim($sina_config->sinapay_mgs_url),
            'sinapay_mas_url' => trim($sina_config->sinapay_mas_url)
        );
        $this->sina_config = $config;
    }

    //网站标识
    const SITE_PREFIX= 'HQW';

    /**
     * 获取配置参数
     * @return null|static
     */
    public static function getConfig(){
        $config = SinaConfig::findOne(['id' => '1']);
        return $config;
    }

    /**
     * 获取商户2的配置参数
     * @return null|static
     */
    public static function getConfigtwo(){
        $config = SinaConfig::findOne(['id' => '2']);
        return $config;
    }


    /**
     * 获取网站资金配置
     * @return bool|null|static
     */
    public static function getsiteConfig(){
        $config = AssetConfig::findOne(['id' => '2']);
        if($config){
            return $config;
        }else{
            return false;
        }

    }
    /**
     * 新浪实名认证
     * @param $uid
     * @param $name
     * @param $idcard
     * @return array
     */
    public static function authentication($uid,$name,$idcard)
    {
        $config = self::getConfig();
        $site_prefix = $config->sinapay_site_prefix;
        $uid = (int)$uid;
        $idcard = trim($idcard);
        $user_ip = Yii::$app->request->userIp;
        $phone = member::getPhone($uid);
        if($phone){
            $flag = self::getIdentity($uid);
            if($flag){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '请勿重复认证',
                    'data' => null
                );
                return $return;
            }
            $res = self::baiduIdentity($idcard);
            if($res){
                $sina = new sina();
                //创建激活会员
                $identity_id = time().$site_prefix.$idcard;
                $create = $sina->create_activate_member($identity_id);
                //以创建激活会员但是实名信息错误
                if(in_array($create['response_code'],array('APPLY_SUCCESS','DUPLICATE_IDENTITY_ID'))){
                    //设置实名信息
                    $realname = $sina->set_real_name($identity_id,$name,$idcard);
                    if($realname['response_code'] == 'APPLY_SUCCESS'){
                        //绑定认证信息
                        $binding = $sina->binding_verify($identity_id,$phone);
                        if($binding['response_code'] == 'APPLY_SUCCESS'){
                            self::memberLog($uid,$identity_id,$name,$idcard,$user_ip,$phone,SinaMember::STATUS_BINGING,$binding['response_message']);
                            $return = array(
                                'errorNum' => '0',
                                'errorMsg' => 'success',
                                'data' => null
                            );
                            return $return;
                        }
                        else{
                            self::memberLog($uid,$identity_id,$name,$idcard,$user_ip,$phone,SinaMember::STATUS_ERROR,$binding['response_message']);
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => $binding['response_message'],
                                'data' => null
                            );
                            return $return;
                        }
                    }else{
						
                        self::memberLog($uid,$identity_id,$name,$idcard,$user_ip,$phone,SinaMember::STATUS_ERROR,$realname['response_message']);
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => $realname['response_message'].'1',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    self::memberLog($uid,$identity_id,$name,$idcard,$user_ip,$phone,SinaMember::STATUS_ERROR,$create['response_message']);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $create['response_message'],
                        'data' => null
                    );
                    return $return;
                }
            }else{
                self::memberLog($uid,'errror',$name,$idcard,$user_ip,$phone,SinaMember::STATUS_ERROR,'身份证号错误');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '身份证号错误',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户被锁定',
                'data' => null
            );
            return $return;
        }

    }

    /**
     * 用户绑定银行卡
     * @param $uid
     * @param $bank_account_no
     * @param $phone_no
     * @return array
     */
    public static function bindingBankCard($uid,$bank_account_no,$phone_no,$post_province='',$post_city='',$post_bank_code=''){
        //绑定一张银行卡
        $isbing = self::isBinding($uid);
        if(!$isbing['errorNum']){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '只支持绑定一张银行卡',
                'data' => null
            );
            return $return;
        }
        //生成绑卡请求号
        $request_no = self::build_order_no();
        //获取用户的新浪标识
        $flag = self::getIdentity($uid);
        if($flag){
            $identity_id = $flag;
            //获取银行卡信息---银行卡的英文缩写-卡的归属省份，城市
            $info = self::bankCardInfo($bank_account_no);
            //接口返回数据
            if($info['errorNum'] == '1'){
                //存在不支持的银行卡或是接口返回其他的信息--或不支持的银行卡
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $info['errorMsg'],
                    'data' => null
                );
                return $return;
            }elseif($info['errorNum'] == '3'){
                //没有查询到归属地---接口错误--用户
                if(!empty($post_province) && !empty($post_city) && !empty($post_bank_code)){
                    $province = $post_province;
                    $city = $post_city;
                    $bank_code = $post_bank_code;
                }else{
                    $return = array(
                        'errorNum' => '3',
                        'errorMsg' => '请填写完整归属地',
                        'data' => $info['data']
                    );
                    return $return;
                }
            }else{
                //银行卡归属省份
                $province = $info['data']['province'];
                //特殊情况
                $zxcity = array('北京','天津','上海','重庆','北京市','天津市','上海市','重庆市');
                //银行卡归属城市
                $city = $info['data']['city'];
                //直辖市没有获取到市的信息
                if(strlen($city) == '1' && in_array($province,$zxcity)){
                    $city = $province;
                }
            }
            //获取银行卡的英文缩写
            if($post_bank_code == ''){
                $bank_code = $info['data']['bank_code'];
            }

            //先默认吧---TODO
            $card_type = 'DEBIT';
            $card_attribute = 'C';

            $bank_branch = '';

            $bank_name = $info['data']['bank_name'];
            //去空格
            $province = trim($province);
            $city = trim($city);
            $bank_code = trim($bank_code);

            $sina = new sina();
            $binding = $sina->binding_bank_card($request_no,$identity_id,$bank_account_no,$phone_no,$bank_code,$card_type,$card_attribute,$province,$city,$bank_branch);
            if($binding['response_code'] == 'APPLY_SUCCESS'){
                $data = array(
                    'ticket' => $binding['ticket'],
                    'request_no' => $request_no
                );
                $res = self::bankLog($uid,$identity_id,$request_no,$bank_code,$bank_name,$bank_account_no,$card_type,$card_attribute,$phone_no,$province,$city,$bank_branch,$binding['ticket'],SinaBank::STATUS_CONFIRM,'等待短信确认');
                if(!$res){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '数据记录失败',
                        'data' => null
                    );
                    return $return;
                }
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                $res = self::bankLog($uid,$identity_id,$request_no,$bank_code,$bank_name,$bank_account_no,$card_type,$card_attribute,$phone_no,$province,$city,$bank_branch,'error',SinaBank::STATUS_ERROR,$binding['response_message']);
                if(!$res){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '数据记录失败',
                        'data' => null
                    );
                    return $return;
                }
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $binding['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行实名认证',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 绑定银行卡确认操作
     * @param $request_no 订单号
     * @param $ticket 推进参数
     * @param $valid_code 手机验证码
     * @return array
     */
    public static function bankCardAdvance($request_no,$ticket,$valid_code){
        $flag = SinaBank::find()->where([
            'request_no' => $request_no,
            'ticket' => $ticket,
            'status' => SinaBank::STATUS_CONFIRM
        ])->one();
        if($flag){
            $sina = new sina();
            $advance = $sina->binding_bank_card_advance($ticket,$valid_code);
            if($advance['response_code'] == 'APPLY_SUCCESS'){
                $flag->status = SinaBank::STATUS_BINGING;
                $flag->card_id = $advance['card_id'];
                $flag->valid_code = $valid_code;
                $flag->msg = $advance['response_message'];
                if($flag->save()){
                    //修改网站账户信息
                    $customer = Info::find()->where(['member_id' => $flag->uid])->one();
                    $customer->bank_card_phone = $flag->phone_no;
                    $customer->bank_card = $flag->bank_account_no;
                    UcenterMember::updateAll(['status' => UcenterMember::STATUS_BIND],['id' => $flag->uid]);
                    if($customer->save()){
                        //赠送绑卡体验金
                        member::give_experience_gold('绑定银行卡',$flag->uid);
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => 'success',
                            'data' => null
                        );
                        return $return;
                    }else{
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '存储错误',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '存储错误',
                        'data' => null
                    );
                    return $return;
                }
            }elseif($advance['response_code'] == 'ILLEGAL_ARGUMENT'){

                $flag->valid_code = $valid_code;
                $flag->msg = $advance['response_message'];
                $flag->status = SinaBank::STATUS_ERROR;
                $flag->save();
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '验证码失效',
                    'data' => null
                );
                return $return;
            }else{
                $flag->valid_code = $valid_code;
                $flag->msg = $advance['response_message'];
                $flag->status = SinaBank::STATUS_ERROR;
                $flag->save();
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $advance['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请重新获取验证码',
                'data' =>null
            );
            return $return;
        }
    }

    /**
     * 查询银行卡绑定信息
     * @param $uid
     * @return array|mixed
     */
    public static function queryBankCard($uid){
        $flag = SinaBank::find()->where([
            'uid' => $uid,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if($flag){
            $identity_id = $flag['identity_id'];
            $card_id = $flag['card_id'];
            $sina = new sina();
            $query = $sina->query_bank_card($identity_id,$card_id);
            if($query['response_code'] == 'APPLY_SUCCESS'){
                $data = array(
                    'card_list' => $query['card_list']
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $query['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户尚未绑定银行卡',
                'data' => null
            );
            return $return;
        }
    }

    /**
     *查询用户绑定银行卡信息
     * @param $uid
     * @return array
     */
    public static function isBinding($uid){
        $flag = SinaBank::find()->where([
            'uid' => $uid,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if($flag){
            $data = array(
                'bank_account_no' => $flag->bank_account_no,
                'bank_code' => $flag->bank_code,
                'bank_name' => $flag->bank_name
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户尚未绑定银行卡',
                'data' => null
            );
            return $return;
        }
    }


    /**
     * 用户托管充值行为
     * @param $uid 用户id
     * @param $amount 充值金额
     * @return array|mixed
     */
    public static function  recharge($uid,$amount){
        $flag = self::getIdentity($uid);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if(!$SinaBank){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行绑定银行卡',
                'data' => null
            );
            return $return;
        }
        //增加银行卡限额配置
        $check = self::checkdeposit($uid,$amount);
        if($check['errorNum']){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $check['errorMsg'],
                'data' => null
            );
            return $return;
        }
        //获取配置中--最小--最大金额
        $config = self::getsiteConfig();
        $deposit_num = $config->deposit_num;
        $deposit_min = $config->deposit_min;
        $deposit_max = $config->deposit_max;
        $today_num = member::get_deposit_num($uid);
        //如果今日有充值行为 -- 次数有限定
        if($today_num && $deposit_num){
            if($today_num > $deposit_num){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '每日限定充值'.$deposit_num.'次',
                    'data' => null
                );
                return $return;
            }
        }
        if($amount < $deposit_min || $amount > $deposit_max || strstr($amount,'.')){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '充值金额在'.$deposit_min.'元与'.$deposit_max.'元之间,且为整数',
                'data' => null
            );
            return $return;
        }
        $out_trade_no = self::build_order_no();
        $identity_id = $SinaBank->identity_id;
        //存钱罐类型用户
        $account_type = 'SAVING_POT';

        $payer_ip = Yii::$app->request->userIp;
        if($payer_ip == '::1')
        {
            $payer_ip = '127.0.0.1';
        }

        //绑定支付 对应存钱罐类型用户
        $pay_type = 'binding_pay';
        $card_id = $SinaBank->card_id;
        $pay_method = $pay_type.'^'.$amount.'^'.$card_id;

        //
        $notify_url = '';
        //网银支付
//        $pay_type = 'online_bank';
//        $bank_code = $SinaBank->bank_code;
//        $card_type = $SinaBank->card_type;
//        $card_attribute = $SinaBank->card_attribute;
//        $pay_method = $pay_type.'^'.$amount.'^'.$bank_code.','.$card_type.','.$card_attribute;
        $sina = new sina();
        $deposit = $sina->create_hosting_deposit($out_trade_no,$identity_id,$account_type,$amount,$payer_ip,$pay_method);
        if(!$deposit){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' =>null
            );
            return $return;
        }
        if($deposit['response_code'] == 'APPLY_SUCCESS'){
            $ticket = $deposit['ticket'];
            $res = self::depositLog($uid,$identity_id,$out_trade_no,$account_type,$amount,$payer_ip,$pay_method,$ticket,SinaDeposit::STATUS_CONFIRM,'等待确认','');
            if(!$res){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据记录失败',
                    'data' => null
                );
                return $return;
            }
            $out_trade_no = $deposit['out_trade_no'];
            $data = array(
                'ticket' => $ticket,
                'out_trade_no' => $out_trade_no
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $deposit['response_message'],
                'data' =>null
            );
            return $return;
        }
    }

    /**
     * 用户充值短信确认
     * @param $out_trade_no 订单号
     * @param $ticket 订单标识
     * @param $validate_code 短信验证码
     * @return array|mixed
     */
    public static function rechargeComfirm($out_trade_no,$ticket,$validate_code){
        $cinfirm = SinaDeposit::find()->where([
            'out_trade_no' => $out_trade_no,
            'ticket' => $ticket,
            'status' => SinaDeposit::STATUS_CONFIRM
        ])->one();
        if(empty($cinfirm)){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请重新获取验证码',
                'data' =>null
            );
            return $return;
        }
        $uid = $cinfirm->uid;
        $amount = $cinfirm->amount;
        $customer = Info::find()->where(['member_id' => $uid])->one();
        if(!$customer){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '网站账户异常',
                'data' =>null
            );
            return $return;
        }
        //获取银行卡号
        $bank_user = self::isBinding($uid);
        $bank_account_no = $uid;
        if(!$bank_user['errorNum']){
            $bank_account_no = $bank_user['data']['bank_account_no'];
        }
        $sina = new sina();
        $pay = $sina->advance_hosting_pay($out_trade_no,$ticket,$validate_code);
        if($pay['response_code'] == 'APPLY_SUCCESS'){

            //支付成功
            $cinfirm->status = SinaDeposit::STATUS_PROCESSING;
            $cinfirm->msg = "处理中";
            $cinfirm->validate_code = $validate_code;
            if($cinfirm->save()){

//                //更新网站的本地账户信息
//                $balance = $customer->balance ? $customer->balance : 0  ;
//                $customer->balance = $amount + $balance;
                //放入冻结金中
                $freeze = $customer->freeze ? $customer->freeze : 0;
                $customer->freeze = $freeze + $amount;
                $flag = $customer->save();

                if($flag){
                    $log = Balance::logSave($uid,$amount,"setBalabce",Log::STATUS_PROCESSING,$bank_account_no,"充值处理中",$out_trade_no);
//                    //更新绑定银行卡金额操作
//                    self::updatebank($uid,$amount);

                    if($log){
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => '充值处理中',
                            'data' =>null
                        );
                        return $return;
                    }else{
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '账户记录失败',
                            'data' =>null
                        );
                        return $return;
                    }
                }else{
                    Balance::logSave($uid,$amount,"setBalabce",Log::STATUS_RECHAR_ERR,$bank_account_no,"更新账户数据失败",$out_trade_no);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '更新账户数据失败',
                        'data' =>null
                    );
                    return $return;
                }
            }
        }elseif($pay['response_code'] =='ADVANCE_TICKET_VALIDATE_FAIL'){
            $cinfirm->status = SinaDeposit::STATUS_ERROR;
            $cinfirm->msg = $pay['response_message'];
            $cinfirm->validate_code = $validate_code;
            if($cinfirm->save()){
                Balance::logSave($uid,$amount,"setBalabce",Log::STATUS_RECHAR_ERR,$bank_account_no,"验证码超时",$out_trade_no);
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '验证码超时,请重新获取',
                    'data' =>null
                );
                return $return;
            }
        }else{
            $cinfirm->status = SinaDeposit::STATUS_ERROR;
            $cinfirm->msg = $pay['response_message'];
            $cinfirm->validate_code = $validate_code;
            if($cinfirm->save()){
                Balance::logSave($uid,$amount,"setBalabce",Log::STATUS_RECHAR_ERR,$bank_account_no,$pay['response_message'],$out_trade_no);
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $pay['response_message'],
                    'data' =>null
                );
                return $return;
            }
        }
    }

    /**
     * 更新用户的充值情况
     * @param $uid
     * @param $money
     * @return string
     */
    public static function updatebank($uid,$money){
        $bank = SinaBank::find()->where(['uid' => $uid,'status' => SinaBank::STATUS_BINGING])->one();
        if($bank !== null){
            //获取最后修改时间---最后充值时间
            $last_time = $bank->update_at;
            //今日零时
            $btime = date('Y-m-d'.'00:00:00',time());
            $btimestr = strtotime($btime);
            //更新今日充值金额和今日充值次数
            if($btimestr < $last_time){
                //今日有更新操作
                $bank->today_money = $bank->today_money + $money;
                $bank->today_deposit_number = $bank->today_deposit_number + 1;
                $bank->total_deposit_number = $bank->total_deposit_number + 1;
               return  $bank->save();
            }else{
                //今日没有更新--没有充值操作--今日记录清零
                $bank->today_money = 0;
                $bank->today_deposit_number = 0;
                $bank->total_deposit_number = $bank->total_deposit_number + 1;
                $flag = $bank->save();
                ///记录数据
                if($flag){
                    $bank->today_money = $money;
                    $bank->today_deposit_number = 1;
                    return $bank->save();
                }else{
                    return $flag;
                }
            }
        }else{
            return false;
        }
    }

    /**
     * 检查用户充值金额
     * @param $uid
     * @param $money
     * @return array
     */
    public static function checkdeposit($uid,$money){
        $bank = SinaBank::find()->where(['uid' => $uid,'status' => SinaBank::STATUS_BINGING])->one();
        //用户已经绑卡
        if($bank !== null){
            //获取最后修改时间---最后充值时间
            $last_time = $bank->update_at;
            //今日零时
            $btime = date('Y-m-d'.'00:00:00',time());
            $btimestr = strtotime($btime);
            //获取总的充值次数
            $total_deposit_number = $bank->total_deposit_number;
            //获取今日充值总金额
            $today_money = $bank->today_money;
            //获取银行卡所属行编码
            $bank_code = $bank->bank_code;
            //获取银行所属银行名称
            $bank_name = $bank->bank_name;
            //获取网站配置的限定额度
            $banklist = BankList::find()->where(['bank_code' => $bank_code,'is_valid' => BankList::IS_VALID_TRUE])->one();
            if($banklist !== null){
                //首次绑卡充值上限
                $binding_pay_1time_limit = $banklist->binding_pay_1time_limit;
                //单笔限额
                $binding_pay_time_limit = $banklist->binding_pay_time_limit;
                //当日充值限额
                $binding_pay_day_limit = $banklist->binding_pay_day_limit;
                //单笔最低充值限额
                $binding_pay_time_min_limit = $banklist->binding_pay_time_min_limit;
                if($money < $binding_pay_time_min_limit){
                    //用户充值小于充值的最小限额
                    $return = array(
                            'errorNum' => '1',
                            'errorMsg' => $bank_name.'单笔最低支付限额'.$binding_pay_time_min_limit.'元',
                            'data' =>null
                    );
                    return $return;
                }
                if($total_deposit_number == 0){
                    //用户是首次充值
                    if($money > $binding_pay_1time_limit){
                        //首次充值金额大于限定值
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => $bank_name.'首次绑卡支付限额为'.$binding_pay_1time_limit.'元',
                            'data' =>null
                        );
                        return $return;
                    }
                }
                if($money > $binding_pay_time_limit){
                    //充值金额大于单笔限额
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $bank_name.'单笔充值限额为'.$binding_pay_time_limit.'元',
                        'data' =>null
                    );
                    return $return;
                }
                //今日有充值行为---今日无充值行为就只判定单笔限额
                if($last_time > $btimestr){
                    //今日剩余的可充值金额
                    $surplus_deposit = $binding_pay_day_limit - $today_money;
                    $surplus_deposit > 0 ? $surplus_deposit : 0;
                    if($money > $surplus_deposit){
                        //总的充值金额大于每日的限定额度
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => $bank_name.'每日充值限额为'.$binding_pay_day_limit.'元',
                            'data' =>null
                        );
                        return $return;
                    }
                }
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' =>null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $bank_name.'不在网站充值支持列表',
                    'data' =>null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户尚未绑卡',
                'data' =>null
            );
            return $return;
        }
    }

    public static function checktodaydeposit($uid){
        //获取配置中--最小--最大金额
        $config = self::getsiteConfig();
        $deposit_num = $config->deposit_num;
        $deposit_min = $config->deposit_min;
        $deposit_max = $config->deposit_max;
        //获取用户绑卡信息
        $bank = SinaBank::find()->where(['uid' => $uid,'status' => SinaBank::STATUS_BINGING])->one();
        //用户已经绑卡
        if($bank !== null){
            //获取最后修改时间---最后充值时间
            $last_time = $bank->update_at;
            //今日零时
            $btime = date('Y-m-d'.'00:00:00',time());
            $btimestr = strtotime($btime);
            //获取今日充值总金额
            $today_money = $bank->today_money;
            //获取总的充值次数
            $total_deposit_number = $bank->total_deposit_number;
            //获取银行卡所属行编码
            $bank_code = $bank->bank_code;
            //获取银行所属银行名称
            $bank_name = $bank->bank_name;
            //获取网站配置的限定额度
            $banklist = BankList::find()->where(['bank_code' => $bank_code,'is_valid' => BankList::IS_VALID_TRUE])->one();

            if($banklist !== null){
                //首次绑卡充值上限
                $binding_pay_1time_limit = $banklist->binding_pay_1time_limit;
                //单笔限额
                $binding_pay_time_limit = $banklist->binding_pay_time_limit;
                //当日充值限额
                $binding_pay_day_limit = $banklist->binding_pay_day_limit;
                //单笔最低充值限额
                $binding_pay_time_min_limit = $banklist->binding_pay_time_min_limit;

                //充值金额达到最大值
                $today_max_money = $deposit_max;//网站设定的每次充值最大金额
                //银行卡限定的单次最大值---首次或者单次
                $bank_limit = $total_deposit_number == 0 ? $binding_pay_1time_limit : $binding_pay_time_limit;
                //
                $bank_limit_max = $bank_limit > $deposit_max ? $deposit_max : $bank_limit;
                //

                $bank_limit_min = $total_deposit_number == 0 ? ($binding_pay_1time_limit > $deposit_min ? $deposit_min : $binding_pay_1time_limit)  : ($binding_pay_time_limit > $deposit_min ? $deposit_min : $binding_pay_time_limit);

                //银行卡限定的单日最大值---每日限额的剩余量
                $bank_limit_day = $binding_pay_day_limit - $today_money;
                //取最小的限定
                $bank_limit = $bank_limit < $bank_limit_day ? $bank_limit : $bank_limit_day;

                //网站最大配置和银行卡最大限制取最小
                $today_max_money = $today_max_money < $bank_limit ? $today_max_money : $bank_limit;

                //配置最小和银行限定最小取最大
                $min_money = $deposit_min > $binding_pay_time_min_limit ? $deposit_min : $binding_pay_time_min_limit;
                //限定最小和每日剩余最小取最大
                $today_min_money = $min_money > $bank_limit_day ? $bank_limit_day : $min_money;
                if($last_time > $btimestr){
                    $data = array(
                        'today_max_money' => $today_max_money,
                        'today_min_money' => $today_min_money
                    );
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' =>$data
                    );
                    return $return;
                }else{
                    //今日没有进行充值行为
                    $data = array(
                        'today_max_money' => $bank_limit_max,
                        'today_min_money' => $bank_limit_min
                    );
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' =>$data
                    );
                    return $return;
                }

            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $bank_name.'不在网站充值支持银行列表列表',
                   'data' =>null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户尚未绑卡',
                'data' =>null
            );
            return $return;
        }
    }

    /**
     * 用户投资---创建托管代收交易--新浪中间账户收款用户
     * @param $uid 投资者id
     * @param $goods_id 标的id
     * @param $money 购买金额
     * @return array
     */
    public static function invest($uid,$goods_id,$money,$summary = "购买标的"){
        $flag = self::getIdentity($uid);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取付款用户的用户信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if(!$SinaBank){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行绑卡操作',
                'data' => null
            );
            return $return;
        }
        $sina = new sina();
        $payer_id = $SinaBank->identity_id;
        $out_trade_no = self::build_order_no();
        //订单有效时间
        $trade_close_time = "1d";
        $payer_ip = Yii::$app->request->userIp;
        if($payer_ip == "::1"){
            $payer_ip = '127.0.0.1';
        }
        //余额支付----对应存钱罐类型用户--新浪判定余额
        $pay_type = 'balance';
        $account_type = "SAVING_POT";
        $pay_method = $pay_type.'^'.$money.'^'.$account_type;
        $ret = self::createHostingCollectTrade($out_trade_no,$summary,$trade_close_time,$goods_id,$payer_id,$payer_ip,$pay_method);
        if($ret['errorNum'] == '0'){
            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_SUCCESS,'投资成功');
            $data = array(
                'out_trade_no' => $out_trade_no,
                'identity_id' => $payer_id,
                'money' => $money
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }
        else{
            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_ERROR,'投资失败'.$ret['errorMsg']);

            $return = array(
                'errorNum' => '1',
                'errorMsg' => $ret['errorMsg'],
                'data' => null
            );
            return $return;
        }

//        $trade = $sina->create_hosting_collect_trade($out_trade_no,$summary,$trade_close_time,$goods_id,$payer_id,$payer_ip,$pay_method);
//        if($trade['response_code'] == 'APPLY_SUCCESS'){
//            //TODO 判定状态
//            if($trade['pay_status'] == 'PROCESSING' || $trade['pay_status'] == 'SUCCESS'){
//                $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_SUCCESS,'投资成功');
//                if(!$res){
//                    $return = array(
//                        'errorNum' => '1',
//                        'errorMsg' => '数据记录失败',
//                        'data' => null
//                    );
//                    return $return;
//                }
//                $data = array(
//                    'out_trade_no' => $out_trade_no,
//                    'identity_id' => $payer_id,
//                    'money' => $money
//                );
//                $return = array(
//                    'errorNum' => '0',
//                    'errorMsg' => 'success',
//                    'data' => $data
//                );
//                return $return;
//            }else{
//                $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_ERROR,'投资失败'.$trade['pay_status']);
//                if(!$res){
//                    $return = array(
//                        'errorNum' => '1',
//                        'errorMsg' => '数据记录失败',
//                        'data' => null
//                    );
//                    return $return;
//                }
//                $return = array(
//                    'errorNum' => '1',
//                    'errorMsg' => '充值失败',
//                    'data' => null
//                );
//                return $return;
//            }
//        }elseif($trade['response_code'] == 'PAY_FAILED'){
//            //用户余额不足
//            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_ERROR,$trade['response_message']);
//            if(!$res){
//                $return = array(
//                    'errorNum' => '1',
//                    'errorMsg' => '数据记录失败',
//                    'data' => null
//                );
//                return $return;
//            }
//            $return = array(
//                'errorNum' => '2',
//                'errorMsg' => $trade['response_message'],
//                'data' => null
//            );
//            return $return;
//        }else{
//            //其他错误
//            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_ERROR,$trade['response_message']);
//            if(!$res){
//                $return = array(
//                    'errorNum' => '1',
//                    'errorMsg' => '数据记录失败',
//                    'data' => null
//                );
//                return $return;
//            }
//            $return = array(
//                'errorNum' => '1',
//                'errorMsg' => $trade['response_message'],
//                'data' => null
//            );
//            return $return;
//        }
    }

    /**
     * 创建托管代收交易 （ 新浪中间账户收钱）
     * @param $out_trade_no 交易订单号
     * @param $summary 交易信息摘要
     * @param $trade_close_time 未付款交易的超时时间 （取值1m～15d）
     * @param $goods_id 商户标的号
     * @param $payer_id 用户标识信息
     * @param $payer_ip 用户请求ip
     * @param $pay_method 支付方式^金额^扩展
     * @return array|mixed
     */
    public static function createHostingCollectTrade($out_trade_no, $summary, $trade_close_time, $goods_id, $payer_id, $payer_ip, $pay_method, $payer_identity_type = "UID"){
        $sina = new sina();
        $trade = $sina->create_hosting_collect_trade($out_trade_no,$summary,$trade_close_time,$goods_id,$payer_id,$payer_ip,$pay_method, $payer_identity_type);
        if($trade['response_code'] == 'APPLY_SUCCESS') {
            // 如果还在处理中，轮询查看状态
            if($trade['pay_status'] == 'PROCESSING'){
                $query = SinaNotifyTrade::find()->where(['outer_trade_no'=>$out_trade_no, 'trade_status'=>['TRADE_FINISHED', 'TRADE_FAILED']]);
                $tradeLog = $query->one(Yii::$app->db2);
                while(empty($tradeLog)){
                    sleep(1);
                    $tradeLog = $query->one(Yii::$app->db2);
                }

                if($tradeLog['trade_status'] == 'TRADE_FINISHED')
                {
                    $ret = array(
                        'errorNum' => '0',
                        'errorMsg' => '交易结束',
                    );
                }else if($tradeLog['trade_status'] == 'TRADE_FAILED'){
                    $ret = array(
                        'errorNum' => '1',
                        'errorMsg' => '交易失败',
                    );
                }
            } elseif($trade['pay_status'] == 'SUCCESS'){
                $ret = array(
                    'errorNum' => '0',
                    'errorMsg' => '交易结束',
                );
            }
            else{
                $ret = array(
                    'errorNum' => '1',
                    'errorMsg' => '支付失败',
                );
            }

        }
        else{
            $ret = array(
                'errorNum' => '1',
                'errorMsg' => $trade['response_message'],
            );
        }

        return $ret;

    }

    /**
     * 创建托管代付交易（投资及满标--网站收钱）
     * @param $out_trade_no 订单号
     * @param $payee_identity_id 收款人信息标识
     * @param $account_type 收款人账户信息
     * @param $amount 收款金额
     * @param $summary 收款摘要
     * @return array|mixed
     */
    public static function createSingleHostingPayTrade($out_trade_no, $payee_identity_id, $account_type, $amount, $summary, $payee_identity_type = "UID")
    {
        $sina = new sina();
        $trade = $sina->create_single_hosting_pay_trade($out_trade_no, $payee_identity_id, $account_type, $amount, $summary, $payee_identity_type);
        if($trade['response_code'] == 'APPLY_SUCCESS') {
            // 如果还在处理中，轮询查看状态
            if($trade['trade_status'] == 'PAY_FINISHED'){
                $query = SinaNotifyTrade::find()->where(['outer_trade_no'=>$out_trade_no, 'trade_status'=>['TRADE_FINISHED', 'TRADE_FAILED']]);
                $tradeLog = $query->one(Yii::$app->db2);
                while(empty($tradeLog)){
                    sleep(1);
                    $tradeLog = $query->one(Yii::$app->db2);
                }

                if($tradeLog['trade_status'] == 'TRADE_FINISHED')
                {
                    $ret = array(
                        'errorNum' => '0',
                        'errorMsg' => '交易结束',
                    );
                }else if($tradeLog['trade_status'] == 'TRADE_FAILED'){
                    $ret = array(
                        'errorNum' => '1',
                        'errorMsg' => '交易失败',
                    );
                }
            } elseif($trade['trade_status'] == 'TRADE_FINISHED'){
                $ret = array(
                    'errorNum' => '0',
                    'errorMsg' => '交易结束',
                );
            }
            else{
                $ret = array(
                    'errorNum' => '1',
                    'errorMsg' => '交易失败',
                );
            }

        }
        else{
            $ret = array(
                'errorNum' => '1',
                'errorMsg' => $trade['response_message'],
            );
        }

        return $ret;
    }

    /**
     * 网站代收用户投资--单个托管代付
     * @param $uid 收款人用户id
     * @param $money 收款金额
     * @param $out_trade_no 投资单号
     * @param $identity_id 投资者用户标识
     * @return array
     */
    public static function sitePeyee($uid,$money,$out_trade_no,$identity_id){
        $invest = SinaInvest::findOne([
            'out_trade_no' => $out_trade_no,
            'identity_id' => $identity_id,
            'money' => $money,
            'status' => SinaInvest::STATUS_SUCCESS
        ]);
        if(!$invest){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '投资信息错误',
                'data' => null
            );
            return $return;
        }
        //获取收款人信息
        $payee_identity_id = self::getIdentity($uid);
        if(!$payee_identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '收款人不存在',
                'data' => null
            );
            return $return;
        }
        $out_trade_no = self::build_order_no();
        $account_type = 'SAVING_POT';
        $summary = "网站收款";
        $sina = new sina();
        $payee = $sina->create_single_hosting_pay_trade($out_trade_no,$payee_identity_id,$account_type,$money,$summary);
        if($payee['response_code'] == 'APPLY_SUCCESS'){
            if($payee['trade_status'] != "PAY_FINISHED"){
                $invest->payee_out_trade_no = $out_trade_no;
                $invest->payee_identity_id = $payee_identity_id;
                $invest->payee_account_type = $account_type;
                $invest->payee_amount = $money;
                $invest->payee_summary = $summary;
                $invest->msg =  '收款失败';
                $invest->status = SinaInvest::STATUS_ERROR;
                if($invest->save()){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '收款失败',
                        'data' => null
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '收款记录失败',
                        'data' => null
                    );
                    return $return;
                }
            }
            $invest->payee_out_trade_no = $out_trade_no;
            $invest->payee_identity_id = $payee_identity_id;
            $invest->payee_account_type = $account_type;
            $invest->payee_amount = $money;
            $invest->payee_summary = $summary;
            $invest->status = SinaInvest::STATUS_PAYEE_SUCCESS;
            $invest->msg = "收款成功";
            if($invest->save()){
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '收款记录失败',
                    'data' => null
                );
                return $return;
            }
        }else{
            $invest->payee_out_trade_no = $out_trade_no;
            $invest->payee_identity_id = $payee_identity_id;
            $invest->payee_account_type = $account_type;
            $invest->payee_amount = $money;
            $invest->payee_summary = $summary;
            $invest->msg =  $payee['response_message'];
            $invest->status = SinaInvest::STATUS_ERROR;
            if($invest->save()){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $payee['response_message'],
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '收款记录失败',
                    'data' => null
                );
                return $return;
            }
        }
    }

    /**
     * 批量托管代付交易
     * @param $payee_uid 代付信息数组类型---uid=>money 需要收款人ID-->收款金额
     * @param $collect_pay_no 对应的代付订单号
     * @return array|mixed
     */
    public static function batchPay($payee_uid,$collect_pay_no){
        $out_pay_no = self::build_order_no();
        //批量代付交易列表
        $list = '';
        $count = 0;
        //判定参数类型
        if(!is_array($payee_uid)){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请以数组类型传递参数',
                'data' => null
            );
            return $return;
        }
        $length = count($payee_uid);
        if($length == 0){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '数组不能为空',
                'data' => null
            );
            return $return;
        }
        foreach($payee_uid as $key=>$value){
            $payee_order = self::build_order_no();
            $payee_identity_id = self::getIdentity($key);
            if(!$payee_identity_id){
                //检测收款人信息
                break;
            }
            $identity_type = 'UID';
            $account_type = 'SAVING_POT';
            $money = $value;
            $summary = '代收投资';
            $count ++;
            //分账参数
            $f_list = '';
            $list .= '$'.$payee_order.'~'.$payee_identity_id.'~'.$identity_type.'~'.$account_type.'~'.$money.'~'.$f_list.'~'.$summary;
        }
        if($count != $length){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '第'.($count+1).'个收款人信息错误',
                'data' => null
            );
            return $return;
        }
        //交易参数
        $trade_list = substr($list,1);
        // 异步通知方式
        $notify_method = 'batch_notify';
        $notify_url = "";
        $sina = new sina();
        $result = $sina->create_batch_hosting_pay_trade($out_pay_no,$trade_list,$notify_method);
        if(!$result){
            $log = self::batchpayLog($out_pay_no,$collect_pay_no,$trade_list,SinaBatchpay::STATUS_ERROR,'新浪接口错误');
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' => null
            );
            return $return;
        }
        if($result['response_code'] == 'APPLY_SUCCESS'){
            $log = self::batchpayLog($out_pay_no,$collect_pay_no,$trade_list,SinaBatchpay::STATUS_SUCCESS,"代付成功");
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $return;
        }else{
            $log = self::batchpayLog($out_pay_no,$collect_pay_no,$trade_list,SinaBatchpay::STATUS_ERROR,$result['response_message']);
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $result['response_message'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 托管退款操作
     * @param $identity_id 用户标示符
     * @param $orig_outer_trade_no 需要退款的订单号
     * @param $refund_amount 需要退款金额
     * @param $summary 退款说明
     * @return array
     */
    public static function hostingRefund($identity_id,$orig_outer_trade_no,$refund_amount,$summary = '投资失败退款'){

        $invest = SinaInvest::findOne([
            'out_trade_no' => $orig_outer_trade_no,
            'identity_id' => $identity_id,
            'money' => $refund_amount,
            'status' => SinaInvest::STATUS_SUCCESS
        ]);
        if(!$invest){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '订单信息错误',
                'data' => null
            );
            return $return;
        }
        $sina = new sina();
        $out_trade_no = self::build_order_no();
        $refund = $sina->create_hosting_refund($out_trade_no,$orig_outer_trade_no,$refund_amount,$summary);
        if(!$refund){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪托管接口失败',
                'data' => null
            );
            return $return;
        }
        if($refund['response_code'] == "APPLY_SUCCESS"){
            //提交信息成功
            if($refund['refund_status'] == 'SUCCESS' || $refund['refund_status'] == 'PAY_FINISHED'){
                //退款成功
                $invest->refund_out_trade_no = $out_trade_no;
                $invest->refund_amount = $refund_amount;
                $invest->refund_summary = '中间账户退款';
                $invest->status = SinaInvest::STATUS_REFUND_SUCCESS;
                $invest->msg = '退款';
                if($invest->save()){
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                }
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据记录失败',
                    'data' => null
                );
                return $return;

            }else{
                $invest->refund_out_trade_no = $out_trade_no;
                $invest->refund_amount = $refund_amount;
                $invest->refund_summary = '中间账户退款';
//                $invest->status = SinaInvest::STATUS_REFUND_ERROR;
                $invest->msg = '退款失败';
                if($invest->save()){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $refund['refund_status'],
                        'data' => null
                    );
                    return $return;
                }
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据记录失败',
                    'data' => null
                );
                return $return;
            }
        }else{
            $invest->refund_out_trade_no = $out_trade_no;
            $invest->refund_amount = $refund_amount;
            $invest->refund_summary = '中间账户退款';
//            $invest->status = SinaInvest::STATUS_REFUND_ERROR;
            $invest->msg = $refund['response_message'];
            if($invest->save()){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $refund['response_message'],
                    'data' => null
                );
                return $return;
            }
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '数据记录失败',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 通过订单号查询退款信息
     * @param $identity_id
     * @param $out_trade_no
     * @return array
     */
    public static function query_hosting_refund_byorder($identity_id,$out_trade_no){
        $sina = new sina();
        $res = $sina->query_hosting_refund_byorder($identity_id,$out_trade_no);
        if(!$res){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' => null
            );
            return $return;
        }
        if($res['response_code'] == 'APPLY_SUCCESS'){
            if(array_key_exists('trade_list',$res)){
                $trade_list = $res['trade_list'];
                //处理订单信息
                $data =  explode('^',$trade_list);
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '无订单信息',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $res['response_message'],
                'data' => null
            );
            return $return;
        }
    }
    /**
     * 用户提现--网站处理
     * @param $uid
     * @param $money
     * @return array
     */
    public static function withdraw($uid,$money){
        $flag = self::getIdentity($uid);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取付款用户的用户信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if(!$SinaBank){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行绑卡操作',
                'data' => null
            );
            return $return;
        }
        $identity_id = $SinaBank->identity_id;
        $card_id = $SinaBank->card_id;
        $out_trade_no = self::build_order_no();
        //获取用户在网站的账户金额
        $site_balance = self::getBalance($uid);
        //获取用户在新浪的账户金额
        $sina = new sina();
        $sina_balance = $sina->query_balance($identity_id);
        $sina_balance = $sina_balance['available_balance'];
//        return $sina_balance;
        //计算用户的货币基金收益
        $money_fund = $sina_balance - $site_balance;
        //网站应该赎回的金额
        $money_site = '0';
        //网站应该向新浪传递的赎回金额
        $money_sina ='0';
        if($money_fund < 0){

            self::withdrawLog($uid,$out_trade_no,$identity_id,$card_id,$site_balance,$sina_balance,$money,$money_fund,$money_site,$money_sina,SinaWithdraw::TYPE_DEF,SinaWithdraw::STATUS_ERROR,"网站账户信息错误");
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '网站账户异常',
                'data' => null
            );
            return $return;
        }
        if($site_balance > $money){
            //网站金额大于提款金额--直接提取账户余额
            $res = self::withdrawLog($uid,$out_trade_no,$identity_id,$card_id,$site_balance,$sina_balance,$money,$money_fund,$money_site,$money_sina,SinaWithdraw::TYPE_ONE,SinaWithdraw::STATUS_SITE_BALANCE,'提现金额小于余额');
            if(!$res){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据记录失败',
                    'data' => null
                );
                return $return;
            }
            $data = array(
                'money_site' => $money_site,
                'money_sina' => $money_sina,
                'identity_id' => $identity_id,
                'out_trade_no' => $out_trade_no
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;

        }else{
            //计算网站应赎回的金额（用户看到的赎回金额）

            $money_site = $money - $site_balance;
            if($sina_balance > $money){
                //用户提款金额小于新浪账户金额 ---网站自己进行赎回操作
                $res = self::withdrawLog($uid,$out_trade_no,$identity_id,$card_id,$site_balance,$sina_balance,$money,$money_fund,$money_site,$money_sina,SinaWithdraw::TYPE_TWO,SinaWithdraw::STATUS_SITE_BALANCE,'提现金额小于新浪账户余额');
                if(!$res){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '数据记录失败',
                        'data' => null
                    );
                    return $return;
                }
                $data = array(
                    'money_site' => $money_site,
                    'money_sina' => $money_sina,
                    'identity_id' => $identity_id,
                    'out_trade_no' => $out_trade_no
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                $money_sina = $money - $sina_balance;
                //用户提款金额大于新浪账户金额--计算需要向新浪传递的赎回金额
                $res = self::withdrawLog($uid,$out_trade_no,$identity_id,$card_id,$site_balance,$sina_balance,$money,$money_fund,$money_site,$money_sina,SinaWithdraw::TYPE_THREE,SinaWithdraw::STATUS_SITE_BALANCE,'提现金额大于新浪账户余额');
                if(!$res){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '数据记录失败',
                        'data' => null
                    );
                    return $return;
                }
                $data = array(
                    'money_site' => $money_site,
                    'money_sina' => $money_sina,
                    'identity_id' => $identity_id,
                    'out_trade_no' => $out_trade_no
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }
        }
    }

    /**
     * 用户赎回--新浪的代收代付 --
     * @param $payee_uid 收钱人（新浪代付的）
     * @param $money_sina 钱数
     * @param $payer_uid 拿钱人（新浪代收的）
     * @return array
     */
    public static function sinaRansom($payee_uid,$money_sina,$payer_uid){
        //获取收款人信息
        $payee_identity_id = self::getIdentity($payee_uid);
        if(!$payee_identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户信息错误',
                'data' => null
            );
            return $return;
        }
        //获取用户需要赎回者的标识--拿钱的人
        $payer_identity_id = self::getIdentity($payer_uid);
        if(!$payer_identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '赎回人信息不存在',
                'data' => null
            );
            return $return;
        }
        //新浪代收---债权人的资金
        $sina = new sina();
        $out_trade_no = self::build_order_no();
        $summary = '用户赎回投资';
        $trade_close_time = '1d';
        $goods_id = 'hqw';
        $payer_ip = Yii::$app->request->userIp;
        if($payer_ip == "::1"){
            $payer_ip = '127.0.0.1';
        }
        //余额支付----对应存钱罐类型用户--新浪判定余额
        $pay_type = 'balance';
        $account_type = "SAVING_POT";
        $pay_method = $pay_type.'^'.$money_sina.'^'.$account_type;

        $ret = self::createHostingCollectTrade($out_trade_no,$summary,$trade_close_time,$goods_id,$payer_identity_id,$payer_ip,$pay_method);
        if($ret['errorNum'] == '0'){
            $res = self::ransomLog($payee_uid,$payee_identity_id,$out_trade_no,$summary,$trade_close_time,$payer_uid,$payer_ip,$pay_method,$money_sina,SinaRansom::STATUS_TRADE,'新浪赎回成功');
            //新浪代收网站金额处理中或者成功---新浪代付给用户--
            $payee_out_trade_no = self::build_order_no();
            $payee_account_type = 'SAVING_POT';
            $payee_amount = $money_sina;
            $payee_summary = '网站返还用户资金';
            //新浪代付给用户资金--进入用户账户
            $pay_trade = self::createSingleHostingPayTrade($payee_out_trade_no,$payee_identity_id,$payee_account_type,$payee_amount,$payee_summary);

            if($pay_trade['errorNum'] == '0'){
                //新浪代付给用户成功
                $res = self::ransomLog($payee_uid,$payee_identity_id,$out_trade_no,$summary,$trade_close_time,$payer_uid,$payer_ip,$pay_method,$money_sina,SinaRansom::STATUS_PAY_TRADE,'新浪返还用户账户成功',$payee_out_trade_no);
                //新浪赎回成功
                //增加订单编号
                $data = array('trade_no' => $out_trade_no);
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                $res = self::ransomLog($payee_uid,$payee_identity_id,$out_trade_no,$summary,$trade_close_time,$payer_uid,$payer_ip,$pay_method,$money_sina,SinaRansom::STATUS_ERROR,'返款错误--'.$pay_trade['errorMsg'],$payee_out_trade_no);

                //增加订单编号
                $data = array('trade_no' => $out_trade_no);
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $pay_trade['errorMsg'],
                    'data' => $data
                );
                return $return;
            }
        }
        else{
            $res = self::ransomLog($payee_uid,$payee_identity_id,$out_trade_no,$summary,$trade_close_time,$payer_uid,$payer_ip,$pay_method,$money_sina,SinaRansom::STATUS_ERROR,'代收错误--'.$ret['errorMsg']);

            //增加订单编号
            $data = array('trade_no' => $out_trade_no);
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $ret['errorMsg'],
                'data' => $data
            );
            return $return;
        }
    }
    /**
     * 用户提现--新浪处理
     * @param $identity_id 用户标识
     * @param $out_trade_no 用户订单号
     * @return array
     */
    public static function sianWithdraw($identity_id,$out_trade_no){
        $sina_withdraw = SinaWithdraw::findOne([
            'identity_id' => $identity_id,
            'out_trade_no' => $out_trade_no,
            'status' => SinaWithdraw::STATUS_SITE_BALANCE
        ]);
        if(!$sina_withdraw){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '订单信息错误',
                'data' => null
            );
            return $return;
        }
        $account_type = 'SAVING_POT';
        $money = $sina_withdraw->money;
        $card_id = $sina_withdraw->card_id;
        //接受异步处理
        $notify_url = '';

        $sina = new sina();
        //用户托管提现
        $withdraw = $sina->create_hosting_withdraw($out_trade_no,$identity_id,$account_type,$money,$card_id,$notify_url);
        if(!$withdraw){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '接口错误',
                'data' => null
            );
            return $return;
        }
        if($withdraw['response_code'] == 'APPLY_SUCCESS'){
            $sina_withdraw->status = SinaWithdraw::STATUS_SINA_DEAL;
            $sina_withdraw->msg = $withdraw['withdraw_status'];
            if($sina_withdraw->save()){
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }
        }else{
            $sina_withdraw->status = SinaWithdraw::STATUS_ERROR;
            $sina_withdraw->msg = $withdraw['withdraw_status'];
            if($sina_withdraw->save()){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $withdraw['response_message'],
                    'data' => null
                );
                return $return;
            }
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '数据记录失败',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 单独新浪提现接口
     * @param $uid
     * @param $money
     * @return array
     */
    public static function sianWithdrawOnly($uid,$money){
        //判定用户是否是新浪注册会员
        $identity_id = self::getIdentity($uid);
        if(!$identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取提现用户的绑卡信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $identity_id,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if(!$SinaBank){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行绑卡操作',
                'data' => null
            );
            return $return;
        }
        $out_trade_no = self::build_order_no();
        $account_type = 'SAVING_POT';
        $card_id = $SinaBank->card_id;
        $sina = new sina();
        //用户托管提现
        $withdraw = $sina->create_hosting_withdraw($out_trade_no,$identity_id,$account_type,$money,$card_id);
        if(!$withdraw){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '接口错误',
                'data' => null
            );
            return $return;
        }
        if($withdraw['response_code'] == 'APPLY_SUCCESS'){
            if($withdraw['withdraw_status'] == 'PROCESSING' || $withdraw['withdraw_status'] == 'SUCCESS'){
                if($withdraw['withdraw_status'] == 'SUCCESS'){
                    $status = SinaWithdraw::STATUS_SINA_SUCCESS;
                    $msg = '新浪处理中';
                }else{
                    $status = SinaWithdraw::STATUS_SINA_DEAL;
                    $msg = '新浪处理成功';
                }
                $log = new SinaWithdraw();
                $log->uid = $uid;
                $log->out_trade_no = $out_trade_no;
                $log->identity_id = $identity_id;
                $log->card_id = $card_id;
                $log->money = $money;
                $log->status = $status;
                $log->type = SinaWithdraw::TYPE_ONLY;
                $log->msg = $msg;
                $save_log = $log->save();
                $data = array('trade_no' => $out_trade_no);
                if($save_log){
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $data
                    );
                    return $return;
                }
            }else{
                $log = new SinaWithdraw();
                $log->uid = $uid;
                $log->out_trade_no = $out_trade_no;
                $log->identity_id = $identity_id;
                $log->card_id = $card_id;
                $log->money = $money;
                $log->status = SinaWithdraw::STATUS_ERROR;
                $log->type = SinaWithdraw::TYPE_ONLY;
                $log->msg = $withdraw['withdraw_status'];
                $save_log = $log->save();
                if($save_log){
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '提现失败',
                        'data' => null
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '数据记录失败',
                        'data' => null
                    );
                    return $return;
                }
            }
        }else{
            $log = new SinaWithdraw();
            $log->uid = $uid;
            $log->out_trade_no = $out_trade_no;
            $log->identity_id = $identity_id;
            $log->card_id = $card_id;
            $log->money = $money;
            $log->status = SinaWithdraw::STATUS_ERROR;
            $log->type = SinaWithdraw::TYPE_ONLY;
            $log->msg = $withdraw['withdraw_status'];
            $save_log = $log->save();
            if($save_log){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $withdraw['response_message'],
                    'data' => null
                );
                return $return;
            }
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '数据记录失败',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户提现查询 ---根据订单号
     * @param $out_trade_no 提现订单号
     * @param $identity_id 提现人标识信息
     * @param string $account_type 账户类型
     * @return array
     */
    public static function query_hosting_withdraw_order($out_trade_no,$identity_id,$account_type = 'SAVING_POT'){
        $sina = new sina();
        $query = $sina->query_hosting_withdraw_order($identity_id,$account_type,$out_trade_no);
        if($query){
            if($query['response_code'] == 'APPLY_SUCCESS'){
                if(array_key_exists('withdraw_list',$query)){
                    $withdraw_list = $query['withdraw_list'];
                    $data = explode('^',$withdraw_list);
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $data
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '无订单信息',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $query['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' => null
            );
            return $return;
        }
    }
    /**
     * 用户一段时间内的提现情况
     * @param $identity_id 用户标识信息
     * @param $start_time 开始时间 ---时间戳
     * @param $end_time 结束时间 -- 时间戳
     * @param string $account_type
     * @return array
     */
    public static function query_hosting_withdraw_time($identity_id,$start_time,$end_time,$account_type = 'SAVING_POT'){
        $start_time = date('YmdHis', $start_time);
        $end_time = date('YmdHis',$end_time);
        $sina = new sina();
        $query = $sina->query_hosting_withdraw_time($identity_id,$account_type,$start_time,$end_time);
        if($query){
            if($query['response_code'] == 'APPLY_SUCCESS'){
                if(array_key_exists('withdraw_list',$query)){
                    $withdraw_list = $query['withdraw_list'];
                    //处理订单信息
                    $data = explode('|',$withdraw_list);
                    foreach($data as $key => $list){
                        $data[$key] = explode('^',$list);
                    }
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $data
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '无订单信息',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $query['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户解绑定银行卡
     * @param $uid
     * @return array
     */
    public static function unbinding_bank_card($uid){
        $flag = self::getIdentity($uid);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取付款用户的用户信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if(!$SinaBank){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户没有绑定银行卡',
                'data' => null
            );
            return $return;
        }
        $identity_id = $flag;
        $card_id = $SinaBank->card_id;
        $sina = new sina();
        $unbinding = $sina->unbinding_bank_card($identity_id,$card_id);
        if($unbinding){
            if($unbinding['response_code'] == 'APPLY_SUCCESS'){
                $SinaBank ->status = SinaBank::STATUS_DELETED;
                $SinaBank->msg = '用户解绑定银行卡';
                if($SinaBank->save()){
                    //修改网站账户信息---清除账户中的信息
                    Info::updateAll(['bank_card_phone' => '','bank_card' => ''],['member_id' => $uid]);
                    UcenterMember::updateAll(['status' => UcenterMember::STATUS_REAL],['id' => $uid]);
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '网站解绑失败',
                        'data' => null
                    );
                    return $return;
                }

            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $unbinding['response_message'],
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '新浪接口错误',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 网站账户对网站用户进行红包发送（网站发钱给用户）
     * @param $money 发放的钱数
     * @param $payee_uid 收款人id值
     * @return array
     */
    public static function collectSite($money,$payee_uid){
        $out_trade_no = self::build_order_no();
        $summary = "网站发放红包";
        //订单有效时间
        $trade_close_time = "1d";
        $payer_ip = Yii::$app->request->userIp;

        //新浪代收--网站企业账户金额---获取网站登陆账户
        $config = self::getConfig();
        $sinapay_site_email = $config->sinapay_site_email;
        ///
        $payer_id = $sinapay_site_email;
        $payer_identity_type = "EMAIL";
        $pay_type = 'balance';
        $pay_account_type = "BASIC";
        $pay_method = $pay_type.'^'.$money.'^'.$pay_account_type;

        $goods_id = 'hqw';
        $uid = 0;
        $ret = self::createHostingCollectTrade($out_trade_no,$summary,$trade_close_time,$goods_id,$payer_id,$payer_ip,$pay_method,$payer_identity_type);
        if($ret['errorNum'] == '0'){
            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$pay_account_type,$goods_id,$money,SinaInvest::STATUS_SUCCESS,'代收网站资金');
            //网站账户资金已经代收----进行批量代付操作
            $batch = self::batchPay($payee_uid,$out_trade_no);
            if($batch['errorNum']){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $batch['errorMsg'],
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }
        }
        else{
            $res = self::investLog($uid,$payer_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,SinaInvest::STATUS_ERROR,'投资失败'.$ret['errorMsg']);

            $return = array(
                'errorNum' => '1',
                'errorMsg' => $ret['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 网站账户收取用户的资金
     * @param $uid 收取用户的uid
     * @param $money 收取的金额
     * @return array|mixed
     */
    public static function collectUser($uid,$money){
        $summary = "网站收利息";
        $goods_id = "hwq";
        $invest = self::invest($uid,$goods_id,$money,$summary);
        if($invest['errorNum']){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $invest['errorMsg'],
                'data' => null
            );
            return $return;
        }else{
            $sina = new sina();
            $out_trade_no = self::build_order_no();
            //新浪代收--网站企业账户金额---获取网站登陆账户
            $config = self::getConfig();
            $payee_identity_id = $config->sinapay_site_email;
            $account_type = "BASIC";
            $amount = $money;
            $payee_identity_type = "EMAIL";
            $pay = $sina->create_single_hosting_pay_trade($out_trade_no,$payee_identity_id,$account_type,$amount,$summary,$payee_identity_type);
            if($pay['response_code'] =="APPLY_SUCCESS"){
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $pay['response_message'],
                    'data' => null
                );
                return $return;
            }
        }
    }
    /**
     * 网站配置账户给用户返还投资利息
     * @param $uid 需要给用户返还的uid
     * @param $money 返还金额
     * @return array
     */
    public static function giveInterest($uid,$money){
        //获取配置中--给利息的人员编号
        $config = self::getConfig();
        $sinapay_give_accrual = $config->sinapay_give_accrual;

        $pay_uid = $sinapay_give_accrual;
        $summary = "给网站返还利息";
        $goods_id = "hwq";
        // 托管代收
        $invest = self::invest($pay_uid,$goods_id,$money,$summary);
        if($invest['errorNum']){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $invest['errorMsg'],
                'data' => null
            );
            return $return;
        }
        //获取收款人信息 ---给用户返还利息
        $payee_identity_id = self::getIdentity($uid);
        if(!$payee_identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '收款人不存在',
                'data' => null
            );
            return $return;
        }
        $out_trade_no = self::build_order_no();
        $account_type = 'SAVING_POT';
        $summary = "网站返还利息";
        $sina = new sina();
        $payee = $sina->create_single_hosting_pay_trade($out_trade_no,$payee_identity_id,$account_type,$money,$summary);
        //获取成功的订单信息
        $orig_outer_trade_no = $invest['data']['out_trade_no'];
        $refund_amount = $invest['data']['money'];
        $identity_id = $invest['data']['identity_id'];
        if($payee['response_code'] == 'APPLY_SUCCESS'){
            //给用户利息失败---进行退款操作
            if($payee['trade_status'] != "PAY_FINISHED"){
                self::hostingRefund($identity_id,$orig_outer_trade_no,$refund_amount,$summary = '返还用户利息失败');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '返还利息失败',
                    'data' => null
                );
                return $return;
            }
            // 怎加返回单号
            $trade_no = $orig_outer_trade_no;
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('trade_no' => $trade_no)
            );
            return $return;
        }else{
            self::hostingRefund($identity_id,$orig_outer_trade_no,$refund_amount,$summary = '返还用户利息失败');
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $payee['response_message'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取用户新浪标识
     * @param $uid
     * @return bool|mixed
     */
    public static function getIdentity($uid){
        $member = SinaMember::find()->where([
            'uid' => $uid,
            'status' => SinaMember::STATUS_BINGING
        ])->one();
        if($member){
            return $member->identity_id;
        }else{
            return false;
        }
    }

    /**
     * 获取用户网站账户金额
     * @param $uid
     * @return bool|mixed
     */
    public static function getBalance($uid){
        $customer = Info::find()->where(['member_id' => $uid])->one();
        if($customer){
            return $customer->balance;
        }else{
            return false;
        }
    }

    /**
     * 获取用户绑卡信息
     * @param $uid
     * @return array
     */
    public static function getBankCard($uid){
        $info = Info::find()->where(['member_id' => $uid])->one();
        if(!$info){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }

        if($info->bank_card != ""){
            $sina_bank = SinaBank::findOne([
                'uid' => $uid,
                'bank_account_no' => $info->bank_card,
                'status' => SinaBank::STATUS_BINGING
            ]);
            if(!$sina_bank){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '用户未绑定银行卡',
                    'data' => null
                );
                return $return;
            }
            $account_no = $sina_bank->bank_account_no;
            $bank_account_no = substr($account_no,0,4).'**** ****'.substr($account_no,-4);
            $data = array(
                'bank_account_no' => $bank_account_no,
                'bank_name' => $sina_bank->bank_name,
                'bank_code' => $sina_bank->bank_code,
                'phone_no' => $sina_bank->phone_no
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户未绑定银行卡',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取用户的详细信息--认证信息--账户信息
     * @param $uid
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getUserInfo($uid){
        $year_rate = '0.08';
        //活动期间利率调整TODO
        date_default_timezone_set('PRC');
        //活动开始时间
        $begin_time = strtotime('2015-9-24');
        //活动结束时间
        $end_time = strtotime('2015-9-30');
        //当前时间
        $now_time = time();
        if($now_time > $begin_time && $now_time < $end_time){
            $year_rate = '0.0815';
        }
        $yes_money = 0;
        $info = UcenterMember::find()->joinWith('info')
            ->select(['id','ucenter_member.status','lock','invitation_code','phone','idcard','real_name','bank_card','bank_card_phone','balance','total_invest','invest','profit','total_revenue'])
            ->where(['id'=>$uid])->asArray()->one();

        if($info == null){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取网站配置信息
        $config = AssetConfig::find()->select(['id','deposit_num','deposit_min','deposit_max','deposit_time','invest_num','invest_min','invest_max','invest_time','withdraw_num','withdraw_min','withdraw_max','withdraw_time','ransom_num','ransom_min','ransom_max','ransom_time'])->where(['id' => '2'])->asArray()->one();

        //获取用户昨日收益金额
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //获取昨日收益
        $get_yes_money = Income::find()->where(['>','created_at',$zero_time])->andWhere(['member_id' => $uid])->one();
        //获取昨日收益金额--利率
        $yes_experience_money = 0;
        if($get_yes_money){
            $yes_money = $get_yes_money->smoney ? $get_yes_money->smoney : 0 ;
            $year_rate = $get_yes_money->rate ? $get_yes_money->rate : 0 ;
            $yes_experience_money = $get_yes_money->goldincome ? $get_yes_money->goldincome : 0 ;
        }
        //获取体验金
        $experience_money = 0;
        $money = Gold::find()->where(['<','created_at',time()])->andWhere(['>','end_at',time()])->andWhere(['uid' => $uid,'status' => Gold::STATUS_ACTIVE])->sum('money');
        if($money){
            $experience_money = sprintf("%.2f", $money);
        }
        //获取活动红包可用金额
        $red_packet_money = 0;
        $red_money = member::get_user_red_packet($uid);
        if(!$red_money['errorNum']){
            $red_packet_money = $red_money['data']['red_sum'];
        }
        $activity_invite = '';
        //用户再投资金大于一元，可以进行分享
//        $invest = $info['invest'] ? $info['invest'] : 0;
//        if($invest >= 1){
//            $activity_invite = AloneMethod::encrypt($info['phone']);
//        }
        $activity_invite = AloneMethod::encrypt($info['phone']);
        $data = array(
            'phone' => $info['phone'],
            'idcard' => $info['idcard'],
            'real_name' => $info['real_name'],
            'status' => $info['status'],
            'lock' => $info['lock'],
            'invitation_code' => $info['invitation_code'],
            'bank_card' => $info['bank_card'],
            'bank_card_phone' => $info['bank_card_phone'],
            'balance' => $info['balance'],
            'total_invest' => $info['total_invest'],
            'total_revenue' => $info['total_revenue'],
            'invest' => $info['invest'],
            'profit' => $info['profit'],
            'yesterday_rate' => sprintf("%.4f", $year_rate),
            'yesterday_money' => $yes_money,
            'experience_money' => $experience_money,
            'yes_experience_money' => $yes_experience_money,
            'red_packet_money' => $red_packet_money,
            'activity_invite' => $activity_invite,
            'config' => $config
        );
        $return = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $return;
    }

    /**
     * 查询新浪账户金额
     * @param $uid  用户id
     * @param string $identity_type
     * @param string $account_type
     * @return array
     */
    public static function querySinaBalance($uid,$identity_type = 'UID',$account_type = 'SAVING_POT'){
        $identity_id = self::getIdentity($uid);
        if(!$identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户未托管',
                'data' => null
            );
            return $return;
        }
        $sina = new sina();
        $balance = $sina->query_balance($identity_id,$identity_type,$account_type);
        if($balance['response_code'] == 'APPLY_SUCCESS'){
            //基金账户
            if(array_key_exists('bonus',$balance)){
                $bonus = explode('^',$balance['bonus']);
                $data = array(
                    'available_balance' => $balance['available_balance'],
                    'balance' => $balance['balance'],
                    'bonus' => $balance['bonus'],
                    'day' => $bonus['0'],
                    'month' => $bonus['1'],
                    'sum' => $bonus['2']
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                //普通账户
                $data = array(
                    'available_balance' => $balance['available_balance'],
                    'balance' => $balance['balance']
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }

        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $balance['response_message'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取网站企业账户金额
     * @return array
     */
    public static function querySiteSinaBalance(){
        $sinapay_partner_id = '200004227922';
        $sina = new sina();
        $balance = $sina->query_balance($sinapay_partner_id,'MEMBER_ID','"BASIC"');
        if($balance['response_code'] == 'APPLY_SUCCESS'){
            //普通账户
            $data = array(
                'available_balance' => $balance['available_balance'],
                'balance' => $balance['balance']
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $balance['response_message'],
                'data' => null
            );
            return $return;
        }
    }







    /**
     * 网站冻结用户新浪账户金额
     * @param $uid 网站id
     * @param $money 冻结金额
     * @param $summary 冻结原因
     * @return array
     */
    public static function balanceFreeze($uid,$money,$summary){
        $identity_id = self::getIdentity($uid);
        if(!$identity_id){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户未托管',
                'data' => null
            );
            return $return;
        }
        $out_freeze_no = self::build_order_no();
        $account_type = 'SAVING_POT';
        $sina = new sina();
        $freeze = $sina->balance_freeze($out_freeze_no,$identity_id,$account_type,(double)$money,$summary);
        if($freeze['response_code'] == 'APPLY_SUCCESS'){
            $log = new SinaFreeze();
            $log->uid = $uid;
            $log->identity_id = $identity_id;
            $log->account_type = $account_type;
            $log->out_freeze_no = $out_freeze_no;
            $log->freeze_money = $money;
            $log->freeze_summary = $summary;
            $log->status = SinaFreeze::STATUS_FREEZE;
            $log->msg = '退款成功';
            $log->save();
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $return;
        }else{
            $log = new SinaFreeze();
            $log->uid = $uid;
            $log->identity_id = $identity_id;
            $log->account_type = $account_type;
            $log->out_freeze_no = $out_freeze_no;
            $log->freeze_money = $money;
            $log->freeze_summary = $summary;
            $log->status = SinaFreeze::STATUS_ERROR;
            $log->msg = '退款失败';
            $log->save();
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $freeze['response_message'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 解冻资金
     * @param $out_freeze_no 需要解冻的当单号
     * @param $money 解冻金额
     * @param $summary 解冻原因
     * @return array
     */
    public static function balanceUnfreeze($out_freeze_no,$money,$summary){
        $flag = SinaFreeze::findOne([
            'out_freeze_no' => $out_freeze_no,
            'status' => SinaFreeze::STATUS_FREEZE
        ]);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '解冻订单信息错误',
                'data' => null
            );
            return $return;
        }
        $identity_id = $flag->identity_id;
        $freeze_money = $flag->freeze_money;
        if($freeze_money < $money){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '解冻金额不能大于冻结金额',
                'data' => null
            );
            return $return;
        }

        $sina = new sina();
        $out_unfreeze_no = self::build_order_no();
        $unfreeze = $sina->balance_unfreeze($out_unfreeze_no,$out_freeze_no,$identity_id,$money,$summary);
        if($unfreeze['response_code'] == 'APPLY_SUCCESS'){
            $unfreeze_money = $flag->unfreeze_money;
            $flag->unfreeze_money = $unfreeze_money + $money;
            $flag->status = SinaFreeze::STATUS_UNFREEZE;
            $flag->msg = '解冻成功';
            $flag->save();
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $return;
        }else{
            $unfreeze_money = $flag->unfreeze_money;
            $flag->unfreeze_money = $unfreeze_money + $money;
            $flag->status = SinaFreeze::STATUS_ERROR;
            $flag->msg = '解冻失败';
            $flag->save();
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $unfreeze['response_message'],
                'data' => null
            );
            return $return;
        }
    }



    /**
     * 百度接口----验证身份证号的正确性
     * @param $idcard
     * @return bool
     * @throws ErrorException
     */
    private static function baiduIdentity($idcard)
    {
        $ch = curl_init();
        $url = 'http://apis.baidu.com/apistore/idservice/id?id='.(string)$idcard;
        $header = array(
            'apikey:c3b6ae43e3bcd04b3edecd96cb075449',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        $flag = json_decode($res);
        curl_close($ch);

        if(is_null($flag) || $flag->retMsg == "success")
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取银行卡信息接口
     * @param $bankcard
     * @return bool|mixed
     */
    public static function bankCardInfo($bankcard){
        $appkey="b7a4079d8c674ad591679d97821555d1";
        $ch = curl_init();
        $url = "http://apis.haoservice.com/lifeservice/bankcard/query?card=".$bankcard."&key=".$appkey;
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        curl_close($ch);
        $flag = json_decode($res);
        //获取支持的银行列表
        $bank_name_list = array();
        $banklist = BankList::find()->where(['is_valid' => BankList::IS_VALID_TRUE])->asArray()->all();
        if($banklist !== null){
            //获取数据库设定的银行列表
            foreach($banklist as $key=>$value){
                $bank_name_list[$key] = array('bank_name' => $value['bank_name'],'bank_code' => trim($value['bank_code']),'bank_logo' => $value['bank_logo']);
            }
        }
        if($flag->error_code){
            //有错误
            $error_msg = array(
                '206101' => '参数错误',
                '206102' => '该卡号暂未收录',
                '206103' => '捕获到一个异常',
                '10001' => '错误的请求KEY',
                '10002' => '该KEY无请求权限',
                '10003' => 'KEY过期',
                '10004' => '错误的SDK KEY',
                '10005' => '应用未审核超时，请提交认证',
                '10007' => '未知的请求源，（服务器没有获取到IP地址）',
                '10008' => '被禁止的IP',
                '10009' => '被禁止的KEY',
                '10011' => '当前IP请求超过限制',
                '10012' => '当前Key请求超过限制',
                '10013' => '测试KEY超过请求限制',
                '10020' => '接口维护',
                '10021' => '接口停用',
                '10022' => 'appKey按需剩余请求次数为零',
                '10023' => '请求IP无效',
                '10024' => '网络错误',
                '10025' => '没有查询到结果',
                '10026' => '当前请求频率过高超过权限限制'
            );
            $msg = 'error';
            if(array_key_exists($flag->error_code,$error_msg)){
                $msg = $error_msg[$flag->error_code];
            }

            $data = array
            (
                'province' => '',
                'city' => '',
                'bank_code' => '',
                'bank_name' => '',
                'banklist' => $bank_name_list
            );
            //接口出现问题---直接用户手工选择
            $return = array(
                'errorNum' => '3',
                'errorMsg' => $msg,
                'data' => $data
            );
            return $return;
        }else{
            //设定一个默认数据列表
            $bank_code = array(
                '95588' => 'ICBC', //工商银行
                '95599' => 'ABC', //农业银行
                '95566' => 'BOC', //中国银行
                '95533' => 'CCB', //建设银行
                '95528' => 'SPDB', //上海浦东发展银行
                '95561' => 'CIB', //兴业银行
                '95595' => 'CEB', //光大银行
                '95568' => 'CMBC', //民生银行
                '95558' => 'CITIC', //中信银行
                '95555' => 'CMB', //招商银行
                '95580' => 'PSBC', //邮政储蓄
                '95511' => 'SZPAB',//平安银行
                '95508' => 'GDB', //广发银行
                '95577' => 'HXB', //华夏银行
                '95594' => 'BOS', //上海银行
            );
            $bank_name = array();
            //获取支持的银行列表
            $banklist = BankList::find()->where(['is_valid' => BankList::IS_VALID_TRUE])->asArray()->all();
            if($banklist !== null){
                //获取数据库设定的银行列表
                $bank_code = array();
                foreach($banklist as $key=>$value){
                    $bank_code[trim($value['service_tel'])] = trim($value['bank_code']);
                    $bank_name[trim($value['bank_name'])] = trim($value['bank_code']);
                }
            }
            $province = trim($flag->result->province);
            $city = trim($flag->result->city);
            $bank = $flag->result->bank;
            $type = $flag->result->type;
            $tel = $flag->result->tel;
            $cardname = $flag->result->cardname;
            $bankcode = "";
            if(array_key_exists($tel,$bank_code)|| array_key_exists($bank,$bank_name)){
                //获取支持的银行卡列表中银行的英文缩写
                if($tel != ''){
                    $bankcode = $bank_code[$tel];
                }else{
                    $bankcode = $bank_name[$bank];
                }
                //获取银行卡的归属地--缺少省份或城市
                $zxcity = array('北京','天津','上海','重庆','北京市','天津市','上海市','重庆市');
               if(!in_array($province,$zxcity)){
                    //不在四大直辖市
                    if($province == '该卡归属地信息暂未收录'){
                        $data = array(
                            'province' => '',
                            'city' => '',
                            'bank_code' => $bankcode,
                            'bank_name' => $bank,
                            'banklist' => $bank_name_list
                        );
                        $return = array(
                            'errorNum' => '3',
                            'errorMsg' => '该卡归属地信息暂未收录',
                            'data' => $data
                        );
                        return $return;
                    }elseif(empty($city) || empty($province)){
                        $data = array(
                            'province' => $province,
                            'city' => $city,
                            'bank_code' => $bankcode,
                            'bank_name' => $bank,
                            'banklist' => $bank_name_list
                        );
                        $return = array(
                            'errorNum' => '3',
                            'errorMsg' => '该卡归属地信息不全',
                            'data' => $data
                        );
                        return $return;
                    }
                }
                $data = array(
                    'province' => trim($province),
                    'city' => trim($city),
                    'bank_code' => $bankcode,
                    'bank_name' => $bank,
                    'type' => $type,
                    'cardname' => $cardname
                );
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return;
            }else{
                //不支持的银行列表
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '平台暂不支持'.$bank,
                    'data' => null
                );
                return $return;
            }

        }
    }
    /**
     * 生成唯一订单号
     * @return string
     */
    private static function build_order_no()
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    ///////T+0处理 用户提现
    /// 用户账户余额的钱---中间账户----商家2的账户中
    /// 商家2---批量到款到用户的银行卡
    public static function immediate_withdraw ($uid,$money)
    {
        //开启记录
        $log = new SinaWithdrawTwo();
        if(!$log){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '记录失败',
                'data' => null
            );
            return $return;
        }
        //用户是否是新浪用户
        $flag = self::getIdentity($uid);
        if (!$flag) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取提现用户的绑定银行卡的信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        if (!$SinaBank) {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先进行绑卡操作',
                'data' => null
            );
            return $return;
        }
        // 验证信息通过
        $summary = "商户2代收用户提现金额";
        $goods_id = "T+0";
        // 用户投资--新浪代收钱
        $invest = self::invest($uid, $goods_id, $money, $summary);
        if ($invest['errorNum']) {
            //todo
            $log->uid = $uid;
            $log->money = $money;
            $log->identity_id = $flag;
            $log->collect_status = $invest['errorMsg'];
            $log->status = SinaWithdrawTwo::STATUS_ERROR;
            $log->create_time = date('Y-m-d-H-i-s', time());
            $log->save();
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $invest['errorMsg'],
                'data' => null
            );
            return $return;
        } else {
            //新浪账户代收成功 ---代付给商家2
            $sina = new sina();
            $out_trade_no = self::build_order_no();
            //新浪代收--网站企业账户金额---获取网站登陆账户
            //TODO--增加新账户
            $config_two = self::getConfigtwo();
            if(!$config_two){
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '网站内部错误',
                    'data' => null
                );
                return $return;
            }
            //TODO--增加新的商户2账户
            $payee_identity_id = trim($config_two->sinapay_site_email);
            $account_type = "BASIC";
            $amount = $money;
            $payee_identity_type = "EMAIL";
            $summary = '代付给商户2';
            $pay = self::createSingleHostingPayTrade($out_trade_no, $payee_identity_id, $account_type, $amount, $summary, $payee_identity_type);

            if ($pay['errorNum'] == "0") {
                // 代付给商家2成功--商家2进行打款操作

                //进行批量付款到银行卡操作
                $batch_no = self::build_order_no();
                //获取提款人基本信息
                $member = SinaMember::find()->where([
                    'uid' => $uid,
                    'status' => SinaMember::STATUS_BINGING
                ])->one();
                //获取配置中--加密密钥
                $config = self::getConfig();
                $public_key = $config->sinapay_rsa_public__key;
                $weibopay = new Weibopay();
                //进行组合
                $list_no = self::build_order_no();
                //用户名
                $list_name = $weibopay->Rsa_encrypt($member->name, $public_key);
                //身份证
                $list_idcard = $weibopay->Rsa_encrypt($member->idcard, $public_key);
                //银行卡号
                $list_bank_account_no = $weibopay->Rsa_encrypt($SinaBank->bank_account_no, $public_key);

                $bank_name = $SinaBank->bank_name;

                $bank_code = $SinaBank->bank_code;

                $province = $SinaBank->province;
                $city = $SinaBank->city;
                $bank_name_zh = $province . $city . $bank_name;
                $bank_money = $money;
                $card_attribute = $SinaBank->card_attribute;
                $card_type = $SinaBank->card_type;
                //组合
                $sina = new sina();
                $detail_list = $list_no . '^' . $list_name . '^' . $list_idcard . '^' . $list_bank_account_no . '^' . $bank_name . '^' . $bank_code . '^' . $province . '^' . $city . '^' . $bank_name_zh . '^' . $bank_money . '^' . $card_attribute . '^' . $card_type;
                //TODO ---方法内参数传递貌似要修改
                $pay2bank = $sina->create_batch_pay2bank($batch_no, $detail_list);
                if ($pay2bank['response_code'] == "APPLY_SUCCESS") {
                    //处理成功
                    //数据记录
                    $log->uid = $uid;
                    $log->money = $money;
                    $log->identity_id = $flag;
                    $log->collect_status = $invest['errorMsg'];
                    $log->out_trade_no = $out_trade_no;
                    $log->payee_identity_id = $payee_identity_id;
                    $log->summary = $summary;
                    $log->hosting_status = $pay2bank['response_code'];
                    $log->batch_no = $batch_no;
                    $log->list_no = $list_no;
                    $log->list_name = $member->name;
                    $log->list_idcard = $member->idcard;
                    $log->list_bank_account_no = $SinaBank->bank_account_no;
                    $log->detail_list = $detail_list;
                    $log->status = SinaWithdrawTwo::STATUS_CONFIRM;
                    $log->msg = '提现处理中';
                    $log->create_time = date('Y-m-d-H-i-s', time());
                    $log->save();
                    $data = array('trade_no' => $list_no);
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $data
                    );
                    return $return;
                } else {
                    $log->uid = $uid;
                    $log->money = $money;
                    $log->identity_id = $flag;
                    $log->collect_status = $invest['errorMsg'];
                    $log->out_trade_no = $out_trade_no;
                    $log->payee_identity_id = $payee_identity_id;
                    $log->summary = $summary;
                    $log->hosting_status = $pay2bank['response_code'];
                    $log->batch_no = $batch_no;
                    $log->list_no = $list_no;
                    $log->list_name = $member->name;
                    $log->list_idcard = $member->idcard;
                    $log->list_bank_account_no = $SinaBank->bank_account_no;
                    $log->detail_list = $detail_list;
                    $log->status = SinaWithdrawTwo::STATUS_ERROR;
                    $log->msg = $pay2bank['response_message'];
                    $log->create_time = date('Y-m-d-H-i-s', time());
                    $log->save();
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $pay2bank['response_message'],
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $log->uid = $uid;
                $log->money = $money;
                $log->identity_id = $flag;
                $log->collect_status = $pay['errorMsg'];
                $log->out_trade_no = $out_trade_no;
                $log->payee_identity_id = $payee_identity_id;
                $log->summary = $summary;
                $log->hosting_status = $pay['errorMsg'];
                $log->list_bank_account_no = $SinaBank->bank_account_no;
                $log->status = SinaWithdrawTwo::STATUS_ERROR;
                $log->msg = $pay['errorMsg'];
                $log->create_time = date('Y-m-d-H-i-s', time());
                $log->save();
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $pay['errorMsg'],
                    'data' => null
                );
                return $return;
            }

        }
    }

    /**
     *批量付款到银行卡
     * @param $batch_no 批次号
     * @param $detail_list 交易列表
     * @param $payto_type 到账类型
     * @return array|mixed
     */
    public static function createBatchPay2bank($batch_no, $detail_list, $payto_type = 'FAST')
    {
        $sina = new sina();
        $pay2bank = $sina->create_batch_pay2bank($batch_no, $detail_list, $payto_type);

    }

    /**
     * 批量付到银行卡测试
     * @param $uid
     * @param $money
     * @return array|mixed
     */
    public static function test($uid,$money){
        $flag = self::getIdentity($uid);
        if(!$flag){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
        //获取提现用户的绑定银行卡的信息
        $SinaBank = SinaBank::find()->where([
            'uid' => $uid,
            'identity_id' => $flag,
            'status' => SinaBank::STATUS_BINGING
        ])->one();
        //批量付款到银行操作
        $batch_no = self::build_order_no();
        //组成出款条目 获取提现人信息
        $member = SinaMember::find()->where([
            'uid' => $uid,
            'status' => SinaMember::STATUS_BINGING
        ])->one();

        //获取配置中--加密密钥
        $config = self::getConfig();
        $public_key = $config->sinapay_rsa_public__key;
        $weibopay = new Weibopay();
        //付款到银行卡
        $list_no = self::build_order_no();
        //用户名
        $list_name = $weibopay->Rsa_encrypt($member->name,$public_key);
        //身份证
        $list_idcard = $weibopay->Rsa_encrypt($member->idcard,$public_key);
        //银行卡号
        $list_bank_account_no = $weibopay->Rsa_encrypt($SinaBank->bank_account_no,$public_key);

        $bank_name = $SinaBank->bank_name;

        $bank_code = $SinaBank->bank_code;

        $province = $SinaBank->province;
        $city = $SinaBank->city;
        $bank_name_zh = $province.$city.$bank_name;
        $bank_money = $money;
        $card_attribute = $SinaBank->card_attribute;
        $card_type = $SinaBank->card_type;
        //组合
        $sina = new sina();
        $detail_list = $list_no.'^'.$list_name.'^'.$list_idcard.'^'.$list_bank_account_no.'^'.$bank_name.'^'.$bank_code.'^'.$province.'^'.$city.'^'.$bank_name_zh.'^'.$bank_money.'^'.$card_attribute.'^'.$card_type;
        $pay2bank = $sina->create_batch_pay2bank($batch_no,$detail_list);
        return $pay2bank;
    }

    /**
     * 获取所有用户的账户信息
     */
    public static function get_sina_balance_all(){
        $user_balance = array();
        $count = 0;
        $sina_members = SinaMember::find()->select(['uid','identity_id','name','phone'])->where(['status' => SinaMember::STATUS_BINGING])->asArray()->all();
        if($sina_members){
            foreach($sina_members as $key => $value){
                $balance = self::querySinaBalance($value['uid']);
                if(!$balance['errorNum']){
                    $customer = Info::find()->where(['member_id' => $value['uid']])->asArray()->one();
                    $site_sina = new SiteSinaBalance();
                    $site_sina->uid = $value['uid'];
                    $site_sina->identity_id = $value['identity_id'];
                    $site_sina->phone = $value['phone'];
                    $site_sina->user_name = $value['name'];
                    $site_sina->bank_card = $customer['bank_card'];
                    $site_sina->site_balance = $customer['balance'];
                    $site_sina->sina_balance = $balance['data']['balance'];
                    $site_sina->sina_available_balance = $balance['data']['available_balance'];
                    $site_sina->user_earnings = $balance['data']['available_balance'] - $customer['balance'];
                    $site_sina->sina_bonus = $balance['data']['bonus'];
                    $site_sina->sina_bonus_day = $balance['data']['day'];
                    $site_sina->sina_bonus_month = $balance['data']['month'];
                    $site_sina->sina_bonus_sum = $balance['data']['sum'];
                    $site_sina->create_time = date('Y-m-d:H-i-s');
                    $site_sina->status = SiteSinaBalance::STATUS_SUS;
                    $site_sina->msg = 'success';
                    $site_sina->save();
                    $count++;
                }else{
                    $site_sina = new SiteSinaBalance();
                    $site_sina->uid = $value['uid'];
                    $site_sina->identity_id = $value['identity_id'];
                    $site_sina->phone = $value['phone'];
                    $site_sina->user_name = $value['name'];
                    $site_sina->status = SiteSinaBalance::STATUS_ERR;
                    $site_sina->msg = $balance['errorMsg'];
                    $site_sina->save();
                    continue;
                }
            }
        }
        return $count;
    }

    /**
     * 跟新最近一段时间的数据
     * @param string $time
     */
    public static function get_deal($time = '1'){
        $deal_time = '';
        //获取数据库最后更新时间
        $site_sina = SiteSinaBalance::find()->where(['status' => SiteSinaBalance::STATUS_SUS])->orderBy('id desc')->asArray()->one();
        //提前的时间
        $site_sina_last = time() - $time*3600;
        if($site_sina){
            $site_sina_last = $site_sina['create_at'] - $time*3600;
        }
        $info_log = Log::find()->select(['member_id','bankcard'])->where(['>','create_at',$site_sina_last])->andWhere(['>','status','0'])->asArray()->distinct('member_id')->all();
        if($info_log){
            //在更新之后有新的操作记录
            foreach($info_log as $key => $value){
                $balance = self::querySinaBalance($value['member_id']);
                if(!$balance['errorNum']){
                    $customer = Info::find()->where(['member_id' => $value['member_id']])->asArray()->one();
                    $site_sina = new SiteSinaBalance();
                    $site_sina->uid = $value['member_id'];
                    $sina_member = SinaMember::find()->select(['uid','identity_id','name','phone'])->where(['status' => SinaMember::STATUS_BINGING,'uid' =>$value['member_id']])->asArray()->one();
                    $site_sina->identity_id = $sina_member['identity_id'];
                    $site_sina->phone = $sina_member['phone'];
                    $site_sina->user_name = $sina_member['name'];

                    $site_sina->bank_card = $customer['bank_card'];
                    $site_sina->site_balance = $customer['balance'];
                    $site_sina->sina_balance = $balance['data']['balance'];
                    $site_sina->sina_available_balance = $balance['data']['available_balance'];
                    $site_sina->user_earnings = $balance['data']['available_balance'] - $customer['balance'];
                    $site_sina->sina_bonus = $balance['data']['bonus'];
                    $site_sina->sina_bonus_day = $balance['data']['day'];
                    $site_sina->sina_bonus_month = $balance['data']['month'];
                    $site_sina->sina_bonus_sum = $balance['data']['sum'];
                    $site_sina->create_time = date('Y-m-d:H-i-s');
                    $site_sina->status = SiteSinaBalance::STATUS_SUS;
                    $site_sina->msg = 'success';
                    $site_sina->save();
                }else{
                    $sina_member = SinaMember::find()->select(['uid','identity_id','name','phone'])->where(['status' => SinaMember::STATUS_BINGING,'uid' =>$value['member_id']])->asArray()->one();
                    $site_sina = new SiteSinaBalance();
                    $site_sina->uid = $sina_member['uid'];
                    $site_sina->identity_id = $sina_member['identity_id'];
                    $site_sina->phone = $sina_member['phone'];
                    $site_sina->user_name = $sina_member['name'];
                    $site_sina->status = SiteSinaBalance::STATUS_ERR;
                    $site_sina->msg = $balance['errorMsg'];
                    $site_sina->save();
                    continue;
                }
            }
        }
    }
    /**
     * 创建纪录
     * @param $uid
     * @param $identity_id
     * @param $name
     * @param $idcard
     * @param $user_ip
     * @param $phone
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function memberLog($uid,$identity_id,$name,$idcard,$user_ip,$phone,$status,$msg){
        $log = new SinaMember();
        $log->uid = $uid;
        $log->identity_id = $identity_id;
        $log->name = $name;
        $log->idcard = $idcard;
        $log->user_ip = $user_ip;
        $log->phone = $phone;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();
    }

    /**
     * 创建绑定银行卡操作
     * @param $uid
     * @param $identity_id
     * @param $request_no
     * @param $bank_code
     * @param $bank_name
     * @param $bank_account_no
     * @param $card_type
     * @param $card_attribute
     * @param $phone_no
     * @param $province
     * @param $city
     * @param $bank_branch
     * @param $ticket
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function bankLog($uid,$identity_id,$request_no,$bank_code,$bank_name,$bank_account_no,$card_type,$card_attribute,$phone_no,$province,$city,$bank_branch,$ticket,$status,$msg){
        $log = new SinaBank();
        $log->uid = $uid;
        $log->identity_id = $identity_id;
        $log->request_no = $request_no;
        $log->bank_code = $bank_code;
        $log->bank_name = $bank_name;
        $log->bank_account_no = $bank_account_no;
        $log->card_type = $card_type;
        $log->card_attribute = $card_attribute;
        $log->phone_no = $phone_no;
        $log->province = $province;
        $log->city = $city;
        $log->bank_branch = $bank_branch;
        $log->ticket = $ticket;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();
    }

    /**
     * 用户充值数据记录
     * @param $uid
     * @param $identity_id
     * @param $out_trade_no
     * @param $account_type
     * @param $amount
     * @param $payer_ip
     * @param $pay_method
     * @param $ticket
     * @param $status
     * @param $msg
     * @param $validate_code
     * @return bool
     */
    private static function depositLog($uid,$identity_id,$out_trade_no,$account_type,$amount,$payer_ip,$pay_method,$ticket,$status,$msg,$validate_code){
        $log = new SinaDeposit();
        $log->uid = $uid;
        $log->identity_id = $identity_id;
        $log->out_trade_no = $out_trade_no;
        $log->account_type = $account_type;
        $log->amount = $amount;
        $log->payer_ip = $payer_ip;
        $log->pay_method = $pay_method;
        $log->ticket = $ticket;
        $log->validate_code = $validate_code;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();

    }

    /**
     * 投资数据记录
     * @param $uid
     * @param $identity_id
     * @param $out_trade_no
     * @param $summary
     * @param $trade_close_time
     * @param $payer_ip
     * @param $pay_type
     * @param $account_type
     * @param $goods_id
     * @param $money
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function investLog($uid,$identity_id,$out_trade_no,$summary,$trade_close_time,$payer_ip,$pay_type,$account_type,$goods_id,$money,$status,$msg){
        $log = new SinaInvest();
        $log->uid = $uid;
        $log->identity_id = $identity_id;
        $log->out_trade_no = $out_trade_no;
        $log->summary = $summary;
        $log->trade_close_time = $trade_close_time;
        $log->payer_ip = $payer_ip;
        $log->pay_type = $pay_type;
        $log->account_type = $account_type;
        $log->goods_id = $goods_id;
        $log->money = $money;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();
    }

    /**
     * 用户提现记录
     * @param $uid
     * @param $out_trade_no
     * @param $identity_id
     * @param $card_id
     * @param $site_balance
     * @param $sina_balance
     * @param $money
     * @param $money_fund
     * @param $money_site
     * @param $money_sina
     * @param $type
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function withdrawLog($uid,$out_trade_no,$identity_id,$card_id,$site_balance,$sina_balance,$money,$money_fund,$money_site,$money_sina,$type,$status,$msg){
        $log = new SinaWithdraw();
        $log->uid = $uid;
        $log->out_trade_no = $out_trade_no;
        $log->identity_id = $identity_id;
        $log->card_id = $card_id;
        $log->site_balance = $site_balance;
        $log->sina_balance = $sina_balance;
        $log->money = $money;
        $log->money_fund = $money_fund;
        $log->money_site = $money_site;
        $log->money_sina = $money_sina;
        $log->type = $type;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();
    }

    /**
     * 记录新浪赎回操作
     * @param $uid
     * @param $identity_id
     * @param $out_trade_no
     * @param $summary
     * @param $trade_close_time
     * @param $payer_id
     * @param $payer_ip
     * @param $pay_method
     * @param $money_sina
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function ransomLog($uid,$identity_id,$out_trade_no,$summary,$trade_close_time,$payer_id,$payer_ip,$pay_method,$money_sina,$status,$msg,$payee_out_trade_no = ''){
        $log = new SinaRansom();
        $log->uid = $uid;
        $log->identity_id = $identity_id;
        $log->out_trade_no = $out_trade_no;
        $log->summary = $summary;
        $log->trade_close_time = $trade_close_time;
        $log->payer_id = $payer_id;
        $log->payer_ip = $payer_ip;
        $log->pay_method = $pay_method;
        $log->money_sina = $money_sina;
        $log->status = $status;
        $log->msg = $msg;
        $log->payee_out_trade_no = $payee_out_trade_no;
        return $log->save();
    }

    /**
     * 批量代付记录
     * @param $out_pay_no
     * @param $collect_pay_no
     * @param $trade_list
     * @param $status
     * @param $msg
     * @return bool
     */
    private static function batchpayLog($out_pay_no,$collect_pay_no,$trade_list,$status,$msg){
        $log = new SinaBatchpay();
        $log->out_pay_no = $out_pay_no;
        $log->collect_pay_no = $collect_pay_no;
        $log->trade_list = $trade_list;
        $log->status = $status;
        $log->msg = $msg;
        return $log->save();
    }

    /**
     * 接收回调参数
     */
    public function notify()
    {
        ksort($_REQUEST);
        $weibopay = new Weibopay ();
        //验证签名
        if ($weibopay->checkSignMsg($_REQUEST, $this->sina_config['sign_type'])) {
            $log = new SinaNotify();
            $log->notify_type = $_REQUEST['notify_type'];
            $log->notify_id = $_REQUEST['notify_id'];
            $log->_input_charset = $_REQUEST['_input_charset'];
            $log->notify_time = $_REQUEST['notify_time'];
            $log->sign = $_REQUEST['sign'];
            $log->sign_type = $_REQUEST['sign_type'];
            $log->version = $_REQUEST['version'];


            $log->memo = array_key_exists('memo', $_REQUEST) ? $_REQUEST['memo'] : '';
            $log->error_code = array_key_exists('error_code', $_REQUEST) ? $_REQUEST['error_code'] : '';
            $log->error_message = array_key_exists('error_message', $_REQUEST) ? $_REQUEST['error_message'] : '';

            $log->notify_data = json_encode($_REQUEST);
            if ($log->save()) {
                try{
                    switch ($log->notify_type) {
                        case 'trade_status_sync':
                            $this->notify_trade($_REQUEST);
                            break;
                        case 'refund_status_sync':
                            $this->notify_refund($_REQUEST);
                            break;
                        case 'deposit_status_sync':
                            $this->notify_deposit($_REQUEST);
                            break;
                        case 'withdraw_status_sync':
                            $this->notify_withdraw($_REQUEST);
                            break;
                        case 'batch_trade_status_sync':
                            $this->notify_batch_trade($_REQUEST);
                            break;
                        case 'b2c_batch_pay2bank_status_sync':
                            $this->notify_batch_pay2bank($_REQUEST);
                            break;
                        default:
                            break;
                    }
                }catch(Exception $ex){
                    Yii::error($ex->getMessage(), 'app');
                    Yii::error($ex->getTrace(), 'app');
                }
                echo 'success';
            } else {
                echo 'db_error';
            }

        } else {
            die ("sign error!");
        }
    }

    /**
     * 接收交易回调
     */
    public function notify_trade($data)
    {
        $out_trade_no = $data['outer_trade_no'];
        if (!empty($out_trade_no)) {
            $trade = new SinaNotifyTrade();
            $trade->outer_trade_no = $out_trade_no;
            $trade->inner_trade_no = $data['inner_trade_no'];
            $trade->trade_status = $data['trade_status'];
            $trade->trade_amount = $data['trade_amount'];
            $trade->gmt_create = $data['gmt_create'];
            $trade->gmt_payment = array_key_exists('gmt_payment', $data) ? $data['gmt_payment'] : '';
            $trade->gmt_close = array_key_exists('gmt_close', $data) ? $data['gmt_close'] : '';
            $trade->pay_method = array_key_exists('pay_method', $data) ? $data['pay_method'] : '';
            $trade->save();
        }
    }

    /**
     * 接收提现回调
     */
    public function notify_withdraw($data)
    {
        $out_trade_no = $data['outer_trade_no'];
        if (!empty($out_trade_no)) {
            $trade = new SinaNotifyWithdraw();
            $trade->outer_trade_no = $out_trade_no;
            $trade->inner_trade_no = $data['inner_trade_no'];
            $trade->withdraw_amount = $data['withdraw_amount'];
            $trade->withdraw_status = $data['withdraw_status'];
            $trade->card_id = array_key_exists('card_id', $data) ? $data['card_id'] : '';
            $trade->save();
        }
    }

    /**
     * 接收退款回调
     */
    public function notify_refund($data)
    {
        $out_trade_no = $data['outer_trade_no'];
        if (!empty($out_trade_no)) {
            $trade = new SinaNotifyRefund();
            $trade->outer_trade_no = $out_trade_no;
            $trade->orig_outer_trade_no = $data['orig_outer_trade_no'];
            $trade->inner_trade_no = $data['inner_trade_no'];
            $trade->refund_amount = $data['refund_amount'];
            $trade->refund_status = $data['refund_status'];
            $trade->gmt_refund = $data['gmt_refund'];
            $trade->save();
        }
    }

    /**
     * 接收充值回调
     */
    public function notify_deposit($data)
    {
        $out_trade_no = $data['outer_trade_no'];
        if (!empty($out_trade_no)) {
            $trade = new SinaNotifyDeposit();

            $trade->outer_trade_no = $out_trade_no;
            $trade->inner_trade_no = $data['inner_trade_no'];
            $trade->deposit_amount = $data['deposit_amount'];
            $trade->deposit_status = $data['deposit_status'];
            $trade->pay_method = array_key_exists('pay_method', $data) ? $data['pay_method'] : '';
            $trade->save();

            $success = strtoupper($trade->deposit_status) == 'SUCCESS';
            // 处理新浪充值记录
            $sinaDeposit = SinaDeposit::find()->where(['out_trade_no'=>$out_trade_no])->one();
            if($success){
                $sinaDeposit->status = SinaDeposit::STATUS_SUCCESS;
                $sinaDeposit->msg = "充值成功";
                //更新绑定银行卡金额操作 ---TODO
                $uid = $sinaDeposit->uid;
                $amount = $sinaDeposit->amount;
                self::updatebank($uid,$amount);
            }
            else{
                $sinaDeposit->status = SinaDeposit::STATUS_ERROR;
                $sinaDeposit->msg = "充值失败";
            }
            $sinaDeposit->save();

            // 处理账户余额
            $uid = $sinaDeposit->uid;
            $customer = Info::find()->where(['member_id' => $uid])->one();
            $freeze = $customer->freeze ? $customer->freeze : 0;
            $customer->freeze = $freeze - $trade->deposit_amount;
            if($success){
                $customer->balance += $trade->deposit_amount;
            }
            $customer->save();

            // 处理用户充值记录
            //获取银行卡号
            $bank_user = self::isBinding($uid);
            $bank_account_no = $uid;
            if(!$bank_user['errorNum']){
                $bank_account_no = $bank_user['data']['bank_account_no'];
            }
            $depositLog = Log::find()->where(['member_id'=>$uid, 'trade_no'=>$out_trade_no])->one();;
            while($depositLog == null){
                sleep(1);
                $depositLog = Log::find()->where(['member_id'=>$uid, 'trade_no'=>$out_trade_no])->one();
            }

            if($success){
                $depositLog->status = Log::STATUS_RECHAR_SUC;
                $depositLog->remark = "充值成功";
            }
            else{
                $depositLog->status = Log::STATUS_RECHAR_ERR;
                $depositLog->remark = "充值失败";
            }

            $depositLog->save();
        }
    }

    public function findDepositResult($out_trade_no, $uid){
        $log =  Log::find()->where(['member_id'=>$uid, 'trade_no'=>$out_trade_no])->one();
        $errnum = '2';
        $msg = '未果';
        if($log){
            if($log->status == Log::STATUS_RECHAR_SUC){
                $msg = "充值成功";
                $errnum = '0';
            }
            elseif($log->status == Log::STATUS_RECHAR_ERR){
                $msg = "充值失败";
                $errnum = '1';
            }
            else{
                $msg = '处理中';
            }
        }
        return array(
            'errorNum' => $errnum,
            'errorMsg' => $msg,
            'data' => null,
        );
    }

    /**
     * 接收批次处理回调
     */
    public function notify_batch_trade($data)
    {
        $outer_batch_no = $data['outer_batch_no'];
        if (!empty($outer_batch_no)) {
            $trade = new SinaNotifyBatchTrade();
            $trade->outer_batch_no = $outer_batch_no;
            $trade->inner_batch_no = $data['inner_batch_no'];
            $trade->batch_amount = $data['batch_amount'];
            $trade->batch_quantity = $data['batch_quantity'];
            $trade->batch_status = $data['batch_status'];
            $trade->trade_list = $data['trade_list'];
            $trade->gmt_create = $data['gmt_create'];
            $trade->gmt_finished = array_key_exists('gmt_finished', $data) ? $data['gmt_finished'] : '';
            $trade->save();
        }
    }

    /**
     * 接收批量付款到银行卡结果通知回调
     */
    public function notify_batch_pay2bank($data)
    {
        $batch_no = $data['batch_no'];
        if (!empty($batch_no)) {
            $trade =  new SinaNotifyBatchPay2bank();
            $trade->batch_no = $batch_no;
            $trade->inner_batch_no = $data['inner_batch_no'];
            $trade->batch_amount = $data['batch_amount'];
            $trade->batch_quantity = $data['batch_quantity'];
            $trade->batch_status = $data['batch_status'];
            $trade->trade_list = $data['trade_list'];
            $trade->gmt_create = $data['gmt_create'];
            $trade->gmt_finished = array_key_exists('gmt_finished', $data) ? $data['gmt_finished'] : '';
            $trade->save();
        }
    }
}