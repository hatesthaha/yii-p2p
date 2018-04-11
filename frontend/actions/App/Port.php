<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/30
 * Time: 10:21
 */

namespace  frontend\actions\app;

use common\models\base\site\IdcardLog;
use common\models\base\site\VerifyCode;
use yii\base\Component;
class Port extends Component {

    //状态码
    const  CONFIRM = -1;  //手机验证码发送成功
    const  SUCCEED  = 1; //手机验证信息完成
    const  ERROR = 0;  //发生错误
    //发送类型
    /**
     * 用户实名认证接口
     * @param $uid 用户iu
     * @param $name 用户姓名
     * @param $cardno 用户身份证号
     * @return array
     */
    public static function authentication($uid,$name,$cardno)
    {

        $uid = $uid;
        $res = self::baiduIdentity($cardno);
        if($res){
            $appkey = "81706e72a9e9c4fd31b4a8c77e65c810";
            $ch = curl_init();
            $url = "http://api.id98.cn/api/idcard?appkey=".$appkey."&name=".$name."&cardno=".$cardno;
            // 执行HTTP请求

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch , CURLOPT_URL , $url);
            $res1 = curl_exec($ch);
            curl_close($ch);
            $flag = json_decode($res1);

            if($flag){
                $isok = $flag->isok;
                $code = $flag->code;

                if($isok){
                    if($code == "1"){
                        $address = $flag->data->address;
                        $sex = $flag->data->sex;
                        $birthday = $flag->data->birthday;
                        $remark = "成功";

                        $cardLog = new IdcardLog();
                        $cardLog->uid = $uid;
                        $cardLog->name = $name;
                        $cardLog->idcard = $cardno;
                        $cardLog->status = Port::SUCCEED;
                        $cardLog->address = $address;
                        $cardLog->sex = $sex;
                        $cardLog->birthday = $birthday;
                        $cardLog->remark = $remark;
                        $res = $cardLog->save();
                        if($res){
                            $return = array(
                                'errorNum' => '0',
                                'errorMsg' => 'success',
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
                    }elseif($code == "2"){
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'姓名与身份证不一致');
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '姓名与身份证不一致',
                            'data' => null
                        );
                        return $return;
                    }elseif($code == "3"){
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'无此身份证号码');
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '无此身份证号码',
                            'data' => null
                        );
                        return $return;
                    }else{
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'未知错误');
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '未知错误',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
                    if($code == "11"){
                        $msg = '参数不正确';
                    }elseif($code == "12"){
                        $msg = '商户余额不足';
                    }elseif($code == "13"){
                        $msg = "appkey不存在";
                    }elseif($code == "14"){
                        $msg = "IP被拒绝";
                    }elseif($code == "20"){
                        $msg = "身份证中心维护中";
                    }else{
                        $msg = "其他系统错误，请联系接口方";
                    }
                    self::idcardLog($uid,$name,$cardno,Port::ERROR,'查询失败-----'.$msg);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '查询失败'.$msg,
                        'data' => null
                    );
                    return $return;
                }
            }else{
                self::idcardLog($uid,$name,$cardno,Port::ERROR,'接口连接错误');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '接口连接错误',
                    'data' => null
                );
                return $return;
            }

        }else{
            self::idcardLog($uid,$name,$cardno,Port::ERROR,'身份证格式错误');
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '身份证格式错误',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 百度接口----验证身份证号的正确性
     * @param $idcard  身份证件号
     * @return bool
     * @throws ErrorException
     */
    public static function baiduIdentity($idcard)
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
     *发送手机验证码
     * @param $phone  手机号
     * @param $exptime  有效时间
     * @param $templateid 短信模板编码
     * @return array
     */
    public static function ValidatePhone($phone,$exptime = 2,$templateid = '2001'){
        $code = self::verification(6);
        $param = $code;
        //20秒发送一次验证码
        $time = time()-20;
        $count = VerifyCode::find()
            ->andWhere([
                'type' => 1,
                'field' => $phone,
                'status' => self::CONFIRM
            ])->orderBy('b_time desc')->one();
        if($time > $count['b_time']){
            //生成验证码
            $b_time = time();
            $sms = new VerifyCode();
            $sms->code = $code;
            $sms->field = $phone;
            $sms->type = '1';
            $sms->b_time = $b_time;
            $sms->e_time = $b_time + $exptime*60;
            $sms->status = Port::CONFIRM;
            $sms->remark = '发送成功等待确认';
            $flag = $sms->save();
            if($flag){
                $res = self::sendSms($phone,$param,$templateid);
                //发送成功
                if(!$res->errcode){
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                }
                else{
                    $errcode = array(
                        '1'=>'手机号码格式错误',
                        '2'=>'IP被拒绝',
                        '3'=>'短信模版ID不存在或审核未通过',
                        '4'=>'appkey不存在',
                        '5'=>'param内容数据格式错误（与短信模版的变量数量不相符）',
                        '6'=>'必填参数不正确',
                        '7'=>'用户余额不足',
                        '8'=>'param内容不合规或含违禁词',
                        '9'=>'param内容长度超限',
                        '10'=>'对同一手机号连续发送多条相同信息',
                        '-1'=>'其他原因发送失败，请联系接口提供方'
                    );
                    $errmsg = $errcode[$res->errcode];
                    self::phongLog($phone,$code,Port::ERROR,$errmsg);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $errmsg,
                        'data' => null
                    );
                    return $return;
                }
            }else{
                self::phongLog($phone,$code,Port::ERROR,'数据写入失败');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据写入失败',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '发送验证码频率过快',
                'data' => null
            );
            return $return;
        }
    }
    /**
     *发送手机验证码
     * @param $phone  手机号
     * @param $templateid 短信模板编码
     * @param $money 钱数 ---提现时发送提醒--模板2
     * @return array
     */
    public static function ValidatePhone2($phone,$templateid,$money = ''){
        //有效时间
        $exptime = 2;
        //20秒发送一次验证码
        $time = time()-20;
        $count = VerifyCode::find()
            ->andWhere([
                'type' => 1,
                'field' => $phone,
                'status' => self::CONFIRM
            ])->orderBy('b_time desc')->one();
        if($time > $count['b_time']){
            //生成验证码
            $code = self::verification(6);
            if($money != "" && $templateid == '2'){
                $code = $money;
            }
            $b_time = time();
            $sms = new VerifyCode();
            $sms->code = $code;
            $sms->field = $phone;
            $sms->type = '1';
            $sms->b_time = $b_time;
            $sms->e_time = $b_time + $exptime*60;
            $sms->status = Port::CONFIRM;
            $flag = $sms->save();
            if($flag){
                $res = \frontend\actions\Port::sendSms2($phone,$code,$exptime,$templateid);
                //发送成功
                if(!$res['errorNum']){
                    $return = array(
                        'errorNum' => '0',
                        'errorMsg' => 'success',
                        'data' => null
                    );
                    return $return;
                }
                elseif($res['errorNum'] == '2'){
                    $errmsg = $res['errorMsg'];
                    self::phongLog($phone,$code,Port::ERROR,$errmsg);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => $errmsg,
                        'data' => null
                    );
                    return $return;
                }else{
                    $errmsg = $res['errorMsg'];
                    self::phongLog($phone,$code,Port::ERROR,$errmsg);
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '短信发送失败',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                self::phongLog($phone,$code,Port::ERROR,'数据写入失败');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '数据写入失败',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '发送验证码频率过快',
                'data' => null
            );
            return $return;
        }
    }
    /**
     * 验证手机验证码
     * @param $phone 手机号
     * @param $code 验证码
     * @return array
     */
    public static function checkPhnoe($phone,$code){
        //查找最后一条生效的验证码
        $VerifyCode = VerifyCode::find()->where([
            'field' => $phone,
            'status' => Port::CONFIRM
        ])->orderBy('b_time desc')->one();
        if($VerifyCode){
            $phoneCode = $VerifyCode->code;
            $endTime = $VerifyCode->e_time;
            $now = time();
            if($now < $endTime){
                if($phoneCode == $code){

                    $VerifyCode->status = Port::SUCCEED;
                    $VerifyCode->remark = "验证通过";
                    $res = $VerifyCode->save();
                    if($res){
                        $return = array(
                            'errorNum' => '0',
                            'errorMsg' => 'success',
                            'data' => null
                        );
                        return $return;
                    }else{
                        self::phongLog($phone,$code,Port::CONFIRM,'发送成功，数据保存异常');
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '数据保存异常',
                            'data' => null
                        );
                        return $return;
                    }
                }else{
//                    self::phongLog($phone,$code,Port::CONFIRM,'验证码错误');
                    $return = array(
                        'errorNum' => '1',
                        'errorMsg' => '验证码错误',
                        'data' => null
                    );
                    return $return;
                }
            }else{
                self::phongLog($phone,$code,Port::ERROR,'发送成功，验证码超时');
                $return = array(
                    'errorNum' => '1',
                    'errorMsg' => '验证码超时',
                    'data' => null
                );
                return $return;
            }
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '还未获取验证码',
                'data' => null
            );
            return $return;
        }

    }

    /**
     * 短信接口
     * @param $phone
     * @param $param 短信模板参数列表
     * @param $templateid
     * @return mixed
     */
    private static function sendSms($phone,$param,$templateid){

        $appkey="eddcee3a76d0e5e267044bafbc5b393a";
        $ch = curl_init();
        $url = "http://api.id98.cn/api/sms?appkey=".$appkey."&phone=".$phone."&templateid=".$templateid."&param=".$param;
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        curl_close($ch);
        $flag = json_decode($res);
        return $flag;
    }

    /**
     * 生成验证码
     * @param int $length 验证码程度
     * @param int $type  验证码类型（1：大小写字母加数字 2：小写字母加数字 其他：纯数字）
     * @return string
     */
    private static function verification($length = 16,$type = 3) {

        if($type == 1){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }elseif($type == 2){
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        }else{
            $chars = "0123456789";
        }
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    /**
     * 身份验证错误日志记录
     * @param $uid
     * @param $name
     * @param $idcard
     * @param $status
     * @param $remark
     */
    private static function idcardLog($uid,$name,$idcard,$status,$remark){
        $cardLog = new IdcardLog();
        $cardLog->uid = $uid;
        $cardLog->name = $name;
        $cardLog->idcard = $idcard;
        $cardLog->status = $status;
        $cardLog->remark = $remark;
        $cardLog->save();

    }

    /**
     * 手机验证错误记录
     * @param $phone
     * @param $code
     * @param $status
     * @param $remark
     */
    private static function phongLog($phone,$code,$status,$remark){
        $sms = new VerifyCode();
        $sms->code = $code;
        $sms->field = $phone;
        $sms->type = '1';
        $sms->status = $status;
        $sms->remark = $remark;
        $sms->save();
    }

}