<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/9
 * Time: 9:16
 */
namespace frontend\actions;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\UcenterMember;
use common\models\yeepay\Bindbankcard;
use framework\base\ErrorException;



class Balance extends Action
{
    //操作成功
    const  SUCCEED = 1;
    //操作失败
    const  ERROR = -1;
    //第三方接口错误
    const  THIRD_ERROR = -1;
    /**
     * 获取用户账户信息
     * @param $mid
     * @return array|Info|null
     * @throws ErrorException
     */
    public static function getBalance($mid)
    {
        $asset = Info::find()->where(['member_id' => $mid])->asArray()->one();
        if($asset){
            return $asset;
        }else{
           return false;
        }
    }

    /**
     * 用户进行绑定银行卡的验证操作
     * @param $uid 用户ID
     * @param $cardno 银行卡号
     * @param $idcardno 身份证号
     * @param $username 用户真实姓名
     * @param $phone 银行卡绑定手机号
     * @param $from 用户来源
     * @return bool|string
     * @throws ErrorException
     */
    public static function bindbankcard($uid,$cardno,$idcardno,$username,$phone,$from)
    {
        $uid = $uid;
        $res = self::isBinding($uid);
        if($res){
            throw new ErrorException('不可以重复绑定银行卡');
        }else{
            $res = yeepay::bindbankcard($uid,$cardno,$idcardno,$username,$phone,$from);
            if($res){
                return $res;
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
        if($res){
            $info = Bindbankcard::findOne([
                'uid' => $uid,
                'status' => Balance::SUCCEED,
            ]);
            $customer = Info::find()->where(['member_id' => $uid])->one();
            $customer->bank_card_phone = $info['phone'];
            $customer->bank_card = $info['card_top']."*********".$info['card_last'];
            if($customer->save()){
                return true;
            }else{
                throw new ErrorException('写入错误');
            }
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

                ////充值金额限定判断--最小充值额度，最大充值额度，单人每次充值限额

                ////易宝充值是按-----分计算
                $money = $money*100;
                $yeepay = yeepay::payment($uid,$money);
                if($yeepay){
                    return $yeepay;
                }else{
                    throw new ErrorException('充值接口失败');
                }
            }else{
                throw new ErrorException('请先绑定银行卡');
            }

        }else{
            throw new ErrorException('请先实名认证');
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

        if(is_array($yeepay)){
            $uid = $yeepay['uid'];

            $money = (float)$yeepay['money']/100;

            $customer = Info::find()->where(['member_id' => $uid])->one();
            if($customer){
                $balance = $customer->balance;

                $customer->balance = $money + $balance;
                $flag = $customer->save();
                if($flag){
                    $log = self::logSave($uid,$money,"setBalabce",self::SUCCEED,$yeepay['orderid'],"充值成功");
                    if($log){
                        return "充值成功！";
                    }else{
                        throw new ErrorException("充值记录错误");
                    }
                }else{
                    self::logSave($uid,$money,"setBalabce",self::ERROR,$yeepay['orderid'],"更新账户数据失败");
                    throw new ErrorException("更新账户数据失败");
                }
            }else{
                $customer = new Info();
                $customer->member_id = $uid;
                $customer->balance = $money;
                $flag = $customer->save();
                if($flag){
                    $log = self::logSave($uid,$money,"setBalabce",self::SUCCEED,$yeepay['orderid'],"充值成功");
                    if($log){
                        return "充值成功！";
                    }else{
                        throw new ErrorException("充值记录错误");
                    }
                }else{
                    self::logSave($uid,$money,"setBalabce",self::ERROR,$yeepay['orderid'],"更新账户数据失败");
                    throw new ErrorException("更新账户数据失败");
                }
            }
        }else{
            throw new ErrorException("短息确认失败");
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
            'status' => Balance::SUCCEED,
        ]);
        if($info){
            $data['cardno'] = $info['cardno'];
            $data['idcardno'] = $info['idcardno'];
            $data['username'] = $info['username'];
            $data['phone'] = $info['phone'];
            return $data;
        }else{
            return false;
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
    public  static function logSave($member_id,$step,$action,$status,$bankcard,$remark,$trade_no=''){
        $log = New Log();
        $log->member_id = $member_id;
        $log->step = $step;
        $log->action = $action;
        $log->status = $status;
        $log->bankcard = $bankcard;
        $log->trade_no = $trade_no;
        $log->remark = $remark;
        $res = $log->save();
        return $res;
    }

    /**
     * 获取用户正确操作日志
     * @param $mid
     * @param $action
     * @return static[]
     */
    public  static  function getLog($mid,$action){
        $log = Log::findAll([
            'status' => Balance::SUCCEED,
            'member_id' => $mid,
            'action' => $action,
        ]);
        return $log;
    }

    /**
     * 获取用户错误操作日志
     * @param $mid
     * @param $action
     * @return static[]
     */
    public  static function getErrorLog($mid,$action){
        $log = Log::findAll([
            'status' => Balance::ERROR,
            'member_id' => $mid,
            'action' => $action,
        ]);
        return $log;

    }


    //////////////////////////
    /**
     * 假数据绑定银行卡
     * @param $uid
     * @param $cardno
     * @param $phone
     * @return bool
     */
    public static function bindbankcard2($uid,$cardno,$phone){

        $customer = Info::find()->where(['member_id' => $uid])->one();
        $customer->bank_card = $cardno;
        $customer->bank_card_phone = $phone;
        if($customer->save()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 测试数据---充值行为
     * @param $uid
     * @param $money
     * @return bool
     */
    public static function setBalance2($uid,$money){
        $customer = Info::find()->where(['member_id' => $uid])->one();
        $money1 = $customer->balance;
        $customer->balance = $money + $money1;
        if($customer->save()){
            $log = self::logSave($uid,$money,"setBalabce",self::SUCCEED,'00000',"充值成功");
            return true;
        }else{
            $log = self::logSave($uid,$money,"setBalabce",self::ERROR,'00000',"充值失败");
            return false;
        }

    }



}