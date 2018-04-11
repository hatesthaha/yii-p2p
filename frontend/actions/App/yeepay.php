<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/30
 * Time: 13:54
 */

namespace frontend\actions\app;

use yii\base\Component;
use common\models\yeepay\Bindbankcard;
use common\models\yeepay\Payment;
use common\models\yeepay\Withdraw;
use framework\base\ErrorException;
use framework\yeepay\yeepayClass;
use Yii;
class yeepay extends Component{

    //绑卡状态码
    const  CONFIRM = -1;  //信息发送成功--未确认
    const  SUCCEED  = 1; //验证信息完成--已确认
    const  ERROR = 0; //信息验证失败

    /**
     * 用户进行绑定银行卡的验证操作
     * @param $uid 用户id
     * @param $cardno 银行卡号
     * @param $idcardno 身份证号
     * @param $username 真实姓名
     * @param $phone 银行预留手机
     * @param $from  用户来源（int 1：pc，2：ios，3：android，4：weixin）
     * @return string 成功及返回绑卡请求id
     * @throws ErrorException
     */
    public static function bindbankcard($uid,$cardno,$idcardno,$username,$phone,$from)
    {
        $uid = $uid;
        $identityid = self::verification($uid);
        $requestid = self::verification($cardno);
        $cardno = $cardno;
        $idcardno = $idcardno;
        $username = $username;
        $phone = $phone;
        $userip = Yii::$app->request->userIP;
        if($userip == '::1')
        {
            $userip = '127.0.0.1';
        }
        $from = $from;
        $binding = new yeepayClass();
        $respond = $binding->bindBankcard($identityid,$requestid,$cardno,$idcardno,$username,$phone,$userip);
        //信息通过了验证
        if(is_array($respond) && $respond['requestid'] == $requestid){

            $status = self::CONFIRM;
            $error_msg = json_encode($respond);
            self::bandingLog($uid,$identityid,$requestid,$cardno,$idcardno,$username,$phone,$userip,$from,$status,$error_msg);
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array(
                    'requestid' => $requestid
                )
            );
            return $return;
        }else{
            //信息填写错误
            $status = self::ERROR;
            $error_msg = $respond;
            self::bandingLog($uid,$identityid,$requestid,$cardno,$idcardno,$username,$phone,$userip,$from,$status,$error_msg);
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $respond,
                'data' => null
            );
            return $return;
        }
    }


    /**
     * 确定绑卡操作
     * @param $requestid 绑卡请求id
     * @param $validatecode 手机验证码
     * @return bool  返回绑定成功
     * @throws ErrorException
     */
    public static function bindBankcardConfirm($requestid,$validatecode){
        $binding = new yeepayClass();
        $res = $binding->bindBankcardConfirm($requestid,$validatecode);
        if(is_array($res) && $res['requestid'] == $requestid){

            $back = Bindbankcard::find()->where(['requestid' => $requestid])->one();
            $back->bankcode = $res['bankcode'];
            $back->card_top = $res['card_top'];
            $back->card_last = $res['card_last'];
            $back->status = yeepay::SUCCEED;
            $flag = $back->save();
            if($flag){
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '绑卡信息存储错误',
                    'data' => null
                );
                return $return;
            }
        }else{
            $back = Bindbankcard::find()->where(['requestid' => $requestid])->one();
            $back->status = yeepay::ERROR;
            $back->error_msg = $res;
            $back->save();
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $res,
                'data' => null
            );
            return $return;
        }

    }

    /**
     * 获取支付请求
     * 主要是用户充值操作
     * @param $uid 用户id
     * @param $amount 充值金额---（ 以"分"为单位的整型）
     * @return array  返回用户的支付订单号和验证码发送手机
     * @throws ErrorException
     */
    public static function payment($uid,$amount)
    {
        $uid = (int)$uid;
        $amount = (int)$amount;
        $info = Bindbankcard::findOne([
            'uid' => $uid,
            'status' => yeepay::SUCCEED,
        ]);
        if ($info) {
            $orderid = self::build_order_no();
            $transtime = time();
            $amount = (int)$amount;
            $productname = '充值';
            $identityid = $info->identityid;
            $card_top = $info->card_top;
            $card_last = $info->card_last;
            $phone = $info->phone;
            $orderexpdate = 20;
            $callbackurl = "http://120.27.40.36/yjpay-php-demo/callback.php";
            $userip = Yii::$app->request->userIP;
            if($userip == '::1')
            {
                $userip = '127.0.0.1';
            }
            $pay = new yeepayClass();
            $respond = $pay->directPayment($orderid, $transtime, $amount, $productname, $identityid, $card_top, $card_last, $callbackurl, $userip,$orderexpdate);
            //充值信息通过了验证
            if (is_array($respond) && $respond['orderid'] == $orderid) {
                if ($respond['phone'] == $phone) {
                    /////TODO 测试环境下部发送
                    if (!$respond['smsconfirm']) {
                        //发送支付验证码
                        $result = $pay->sendValidateCode($orderid);
                        if (is_array($result) && $result['orderid'] == $orderid) {

                            $status = yeepay::CONFIRM;
                            $msg = "支付验证已发送";
                            $res = self::paymentLog($uid,$orderid,$transtime,$amount,$productname,$identityid,$orderexpdate,$phone,$userip,$status,$msg);
                            if($res){
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => 'success',
                                    'data' => array(
                                        'orderid' => $orderid,
                                        'phone' => $phone
                                    )
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
                        } else {
                            $status = yeepay::ERROR;
                            $msg = "支付验证码发送失败";
                            self::paymentLog($uid,$orderid,$transtime,$amount,$productname,$identityid,$orderexpdate,$phone,$userip,$status,$msg);
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '发送支付验证码失败',
                                'data' => null
                            );
                            return $return;
                        }
                    }
                    else {
                        //TODO
                        throw new ErrorException("不发送短信");
                    }
                }else {
                    $status = yeepay::ERROR;
                    $msg = "银行预留手机已更改";
                    self::paymentLog($uid,$orderid,$transtime,$amount,$productname,$identityid,$orderexpdate,$phone,$userip,$status,$msg);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '银行预留手机已更改',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $status = yeepay::ERROR;
                $msg = "用户信息错误";
                self::paymentLog($uid,$orderid,$transtime,$amount,$productname,$identityid,$orderexpdate,$phone,$userip,$status,$msg);
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $respond,
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户未绑定银行卡',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 短信确认支付
     * @param $orderid  订单号
     * @param $validatecode 手机验证码
     * @return bool
     * @throws ErrorException
     */
    public static function confirmPayment($orderid, $validatecode){
        $info = Payment::findOne([
            'orderid' => $orderid,
            'status' => yeepay::CONFIRM,
        ]);
        if($info){
            if($validatecode != "" && strlen($validatecode) == 6){
                $pay = new yeepayClass();
                $respond = $pay->confirmPayment($orderid, $validatecode);

                if (is_array($respond) && $respond['orderid'] == $orderid){

                    $info->yborderid = $respond['yborderid'];
                    $info->ybamount = $respond['amount'];
                    $info->msg = "验证中";
                    $flag = $info->save();
                    if($flag){

                        ////TODO callbackurl
                        //必须有等待时间---》进行数据处理
                        sleep(15);
                        //使用交易记录进行订单的查询---确认充值的情况
                        $res =self::paymentQuery($orderid,$respond['yborderid']);


                        if($res['status'] == 0){
                            $info->status = yeepay::ERROR;
                            $info->msg = "充值失败";
                            $info->save();
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '充值失败',
                                'data' => null
                            );
                            return $return;
                        }elseif($res['status'] == 2){
                            $info->msg = "未处理";
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '未处理',
                                'data' => null
                            );
                            return $return;
                        }elseif($res['status'] == 3){
                            sleep(10);
                            $res =self::paymentQuery($orderid,$respond['yborderid']);
                            if($res['status'] == 1){
                                $info->msg = "充值成功";
                                $info->status = yeepay::SUCCEED;
                                $flag = $info->save();
                                if($flag){
                                    $return = array(
                                        'errorNum' => '0',
                                        'errorMsg' => 'success',
                                        'data' => array(
                                            'uid' => $info->uid,
                                            'money' => $res['amount'],
                                            'orderid' => $res['orderid']
                                        )
                                    );
                                    return $return;
                                }
                            }else{
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '操作超时',
                                    'data' => null
                                );
                                return $return;
                            }
                        }
                        elseif($res['status'] == 1){
                            $info->msg = "充值成功";
                            $info->status = yeepay::SUCCEED;
                            $flag = $info->save();
                            if($flag){
                                $return = array(
                                    'errorNum' => '0',
                                    'errorMsg' => 'success',
                                    'data' => array(
                                        'uid' => $info->uid,
                                        'money' => $res['amount'],
                                        'orderid' => $res['orderid']
                                    )
                                );
                                return $return;
                            }else{
                                $return = array(
                                    'errorNum' => '1',
                                    'errorMsg' => '写入数据失败',
                                    'data' => null
                                );
                                return $return;
                            }
                        }else{
                            $info->status = yeepay::ERROR;
                            $info->msg = "未知错误";
                            $info->save();
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '未知错误',
                                'data' => null
                            );
                            return $return;
                        }
                    }
                    else{
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '写入数据库失败',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    $info->msg = (string)$respond;
                    $info->save();
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => (string)$respond,
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '请正确填写验证码',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '订单号错误',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 交易记录查询
     * @param $orderid
     * @param $yborderid
     * @return array
     * @throws ErrorException
     */
    public static function paymentQuery($orderid,$yborderid){
        $orderid = $orderid;
        $yborderid = $yborderid;
        $pay = new yeepayClass();
        $respond = $pay->paymentQuery($orderid,$yborderid);
        if (is_array($respond)){
            return $respond;
        }else{
            throw new ErrorException($respond);
        }
    }

    /**
     * 用户提现操作----T+0模式
     * @param $uid 用户id
     * @param $amount 用户提现金额
     * @return bool
     * @throws ErrorException
     */
    public static function withdraw($uid,$amount){
        $info = Bindbankcard::findOne([
            'uid' => $uid,
            'status' => yeepay::SUCCEED
        ]);
        if($info){
            $identityid = $info->identityid;
            $requestid = self::verification();
            $card_top = $info->card_top;
            $card_last = $info->card_last;
            $userip = Yii::$app->request->userIP;
            $pay = new yeepayClass();
            $respond = $pay->withdraw($requestid, $identityid, $card_top, $card_last, $amount, $userip);
            //充值信息验证成功
            if (is_array($respond) && $respond['requestid'] == $requestid){
                $status = $respond['status'];
                if($status == "FAILURE"){
                    $status = yeepay::ERROR;
                    $msg = '提现请求失败';
                    self::withdrawLog($uid,$identityid,$card_top,$card_last,$amount,$userip,$status,$msg,'','');
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '提现请求失败',
                        'data' => null
                    );
                    return $return;
                }elseif($status == 'SUCCESS'){
                    //TODO
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                }else {
                    $status = yeepay::ERROR;
                    $msg = '提现未知错误';
                    self::withdrawLog($uid,$identityid,$card_top,$card_last,$amount,$userip,$status,$msg,'','');
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '提现未知错误',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $status = yeepay::ERROR;
                $msg = $respond;
                self::withdrawLog($uid,$identityid,$card_top,$card_last,$amount,$userip,$status,$msg,'','');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => $respond,
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '未有绑定银行卡',
                'data' => null
            );
            return $return;
        }
    }



    /**
     * 银行卡绑定查询
     * @param $identityid
     * @return \framework\yeepay\type
     */
    public static function bankcardList($identityid){
        $pay = new yeepayClass();
        $respond = $pay->bankcardList($identityid);
        if (is_array($respond) && count($respond['cardlist'])){
            return $respond;
        }else{
            return false;
        }
    }



    /**
     * 生成唯一订单号
     * @return string
     */
    public static function build_order_no()
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 生成唯一字符串作为标识
     * @param $type
     * @return string
     */
    private static function verification($type='') {
        $length =6;
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return md5(microtime().$str.$type).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 记录用户绑定信息
     * @param $uid
     * @param $identityid
     * @param $requestid
     * @param $cardno
     * @param $idcardno
     * @param $username
     * @param $phone
     * @param $userip
     * @param $from
     * @param string $status
     * @param string $error_msg
     * @param string $bankcode
     * @param string $card_top
     * @param string $card_last
     * @return bool
     *
     */
    private static function bandingLog($uid,$identityid, $requestid, $cardno, $idcardno, $username, $phone, $userip,$from,$status='',$error_msg='',$bankcode='',$card_top='',$card_last=''){
        $bind = new Bindbankcard();
        $bind->uid = $uid;
        $bind->identityid = $identityid;
        $bind->requestid = $requestid;
        $bind->cardno = $cardno;
        $bind->idcardno = $idcardno;
        $bind->username = $username;
        $bind->phone = $phone;
        $bind->userip = $userip;
        $bind->from = $from;
        $bind->status = $status;
        $bind->error_msg = $error_msg;
        $bind->bankcode = $bankcode;
        $bind->card_top = $card_top;
        $bind->card_last = $card_last;
        $res = $bind->save();
        return $res;
    }

    /**
     * 记录用户的充值记录
     * @param $uid
     * @param $orderid
     * @param $transtime
     * @param $amount
     * @param $productname
     * @param $identityid
     * @param $orderexpdate
     * @param $phone
     * @param $userip
     * @param $status
     * @param $msg
     * @param string $sendtime
     * @param string $yborderid
     * @param string $ybamount
     * @return bool
     */
    private static function paymentLog($uid,$orderid,$transtime,$amount,$productname,$identityid,$orderexpdate,$phone,$userip,$status,$msg,$sendtime='',$yborderid='',$ybamount=''){
        $pay = new Payment();
        $pay->uid = (int)$uid;
        $pay->orderid = $orderid;
        $pay->transtime = $transtime;
        $pay->amount = (int)$amount;
        $pay->productname = $productname;
        $pay->identityid = $identityid;
        $pay->orderexpdate =(int) $orderexpdate;
        $pay->phone = $phone;
        $pay->userip = $userip;
        $pay->status = $status;
        $pay->msg = $msg;
        $pay->sendtime = $sendtime;
        $pay->yborderid = $yborderid;
        $pay->ybamount = $ybamount;
        $flag = $pay->save();
        return $flag;
    }

    /**
     * 记录用户提现操作
     * @param $uid
     * @param $identityid
     * @param $card_top
     * @param $card_last
     * @param $amount
     * @param $userip
     * @param $ybamount
     * @param $ybdrawflowid
     * @param $status
     * @param $msg
     */
    private static function withdrawLog($uid,$identityid,$card_top,$card_last,$amount,$userip,$status,$msg,$ybamount,$ybdrawflowid)
    {
        $withdraw = new Withdraw();
        $withdraw->uid = $uid;
        $withdraw->identityid = $identityid;
        $withdraw->card_top = $card_top;
        $withdraw->card_last = $card_last;
        $withdraw->amount = $amount;
        $withdraw->userip = $userip;
        $withdraw->ybamount = $ybamount;
        $withdraw->ybdrawflowid = $ybdrawflowid;
        $withdraw->status = $status;
        $withdraw->msg = $msg;
        $withdraw->save();

    }


}