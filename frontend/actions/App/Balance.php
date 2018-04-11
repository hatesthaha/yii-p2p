<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/30
 * Time: 15:06
 */

namespace frontend\actions\app;

use frontend\actions\member;
use yii\base\Component;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\UcenterMember;
use common\models\yeepay\Bindbankcard;
class Balance extends Component{


    //操作成功
    const  SUCCEED = 1;
    //操作失败
    const  ERROR = 0;
    //第三方接口错误
    const  THIRD_ERROR = -1;

    /**
     * 获取用户账户信息
     * @param $uid 用户id
     * @return array
     */
    public static function getBalance($uid)
    {
        $asset = Info::find()->where(['member_id' => $uid])->asArray()->one();
        if($asset){
            $info = array(
                'bank_card' => $asset['bank_card'],
                'bank_card_phone' => $asset['bank_card_phone'],
                'balance' => $asset['balance'],
                'freeze' => $asset['freeze']
            );
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $info
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户进行绑定银行卡的验证操作
     * @param $uid 用户ID
     * @param $cardno 银行卡号
     * @param $phone 银行卡绑定手机号
     * @param $from 用户来源
     * @return bool|string
     * @throws ErrorException
     */
    public static function bindbankcard($uid,$cardno,$phone,$from)
    {
        $uid = $uid;
        $res = self::isBinding($uid);
        if($res){
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '不可以重复绑定银行卡',
                'data' => null
            );
            return $return;
        }else{
            $is = member::isAuthentic($uid);
            if($is){
                $idcardno = $is['idcard'];
                $username = $is['real_name'];
                $res = yeepay::bindbankcard($uid,$cardno,$idcardno,$username,$phone,$from);
                if(!$res['errorNum']){
                    $info = $res['data'];
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $info
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $res['errorMsg'],
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '请先实名认证',
                    'data' => null
                );
                return $return;
            }
        }
    }

    /**
     * 用户确认绑卡操作
     * @param $uid  用户id
     * @param $requestid 请求资源id（验证通过后返回值）
     * @param $validatecode 手机验证码
     * @return bool
     * @throws ErrorException
     */
    public static function bindBankcardConfirm($uid,$requestid,$validatecode)
    {
        $uid = $uid;
        $res = yeepay::bindBankcardConfirm($requestid,$validatecode);
        if(!$res['errorNum']){
            $info = Bindbankcard::findOne([
                'uid' => $uid,
                'status' => Balance::SUCCEED,
            ]);
            $customer = Info::find()->where(['member_id' => $uid])->one();
            $customer->bank_card_phone = $info['phone'];
            $customer->bank_card = $info['card_top']."*********".$info['card_last'];
            if($customer->save()){
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '信息存储失败',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $res['errorMsg'],
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 用户充值行为
     * @param $uid 用户id
     * @param $money 用户金额
     * @return bool
     * @throws ErrorException
     */
    public static function setBalance($uid,$money)
    {
        //判断用户是否存在，用户状态TODO（是否锁定）

        //用户实名
        $res = member::isAuthentic($uid);
        if($res){
            //用户是否绑卡
            $isBinding = Balance::isBinding($uid);
            if($isBinding){
                ////易宝充值是按-----分计算
                $money = $money*100;

                ////充值金额限定判断--最小充值额度，最大充值额度，单人每次充值限额


                $yeepay = yeepay::payment($uid,$money);
                if(!$yeepay['errorNum']){
                    $data = $yeepay['data'];
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => $data
                    );
                    return $return;
                }else{
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $yeepay['errorMsg'],
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '请先绑定银行卡',
                    'data' => null
                );
                return $return;
            }

        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '请先实名认证',
                'data' => null
            );
            return $return;
        }


    }

    /**
     * 用户短信确认充值
     * @param $orderid 充值订单号
     * @param $validatecode 短信验证码
     * @return string
     * @throws ErrorException
     */
    public static function confirmSet($orderid, $validatecode){
        $orderid = (string)$orderid;
        $validatecode = (string)$validatecode;
        $yeepay = yeepay::confirmPayment($orderid,$validatecode);
        //易宝接口完成充值行为
        if(!$yeepay['errorNum']){
            $yeepay = $yeepay['data'];
            $uid = $yeepay['uid'];
            $money = (float)($yeepay['money']/100);

            $customer = Info::find()->where(['member_id' => $uid])->one();
            //写入网站的账户信息
            if($customer){
                $balance = $customer->balance;
                $customer->balance = $money + $balance;
                $flag = $customer->save();
                if($flag){
                    $log = self::logSave($uid,$money,"setBalabce",self::SUCCEED,$yeepay['orderid'],"充值成功");
                    if($log){
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => 'success',
                            'data' => null
                        );
                        return $return;
                    }else{
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '充值记录错误',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    self::logSave($uid,$money,"setBalabce",self::ERROR,$yeepay['orderid'],"更新账户数据失败");
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '更新账户数据失败',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                $customer = new Info();
                $customer->member_id = $uid;
                $customer->balance = $money;
                $flag = $customer->save();
                if($flag){
                    $log = self::logSave($uid,$money,"setBalabce",self::SUCCEED,$yeepay['orderid'],"充值成功");
                    if($log){
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => 'success',
                            'data' => null
                        );
                        return $return;
                    }else{
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '充值记录错误',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    self::logSave($uid,$money,"setBalabce",self::ERROR,$yeepay['orderid'],"更新账户数据失败");
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '更新账户数据失败',
                        'data' => null
                    );
                    return $return;
                }
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '短息确认失败',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 判定用户是否已经绑定银行卡
     * @param $uid  用户id
     * @return bool
     * @throws ErrorException
     */
    public static function isBinding($uid)
    {
        $info = Bindbankcard::findOne([
            'uid' => $uid,
            'status' => yeepay::SUCCEED,
        ]);
        if($info){
            $data['cardno'] = $info['cardno'];
            $data['phone'] = $info['phone'];
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '未进行绑定',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 保存用户的账户操作记录
     * @param $member_id
     * @param $step
     * @param $action
     * @param $status
     * @param $bankcard
     * @param $remark
     * @return bool
     */
    public  static function logSave($member_id,$step,$action,$status,$bankcard,$remark){
        $log = New Log();
        $log->member_id = $member_id;
        $log->step = $step;
        $log->action = $action;
        $log->status = $status;
        $log->bankcard = $bankcard;
        $log->remark = $remark;
        $res = $log->save();
        return $res;
    }


}