<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/15
 * Time: 15:34
 */
namespace frontend\actions;

use common\models\base\site\IdcardLog;
use common\models\base\site\VerifyCode;
use framework\base\ErrorException;

class Port extends Action
{
    //状态码
    const  CONFIRM = -1;  //手机验证码发送成功
    const  SUCCEED  = 1; //手机验证信息完成
    const  ERROR = 0;  //发生错误

    /**
     * 用户实名认证接口
     * @param $uid 用户id
     * @param $name 真实姓名
     * @param $cardno 身份证号码
     * @throws ErrorException
     */
    public static function authentication($uid,$name,$cardno)
    {
        $uid = (int)$uid;
        $res = self::baiduIdentity($cardno);
        if($res){
            $appkey = "eddcee3a76d0e5e267044bafbc5b393a";
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
                            return true;
                        }else{
                            throw new ErrorException("数据记录失败");
                        }
                    }elseif($code == "2"){
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'姓名与身份证不一致');
                        throw new ErrorException('姓名与身份证不一致');
                    }elseif($code == "3"){
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'无此身份证号码');
                        throw new ErrorException('无此身份证号码');
                    }else{
                        self::idcardLog($uid,$name,$cardno,Port::ERROR,'未知错误');
                        throw new ErrorException('未知错误');
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
                    throw new ErrorException('查询失败'.$msg);
                }
            }else{
                self::idcardLog($uid,$name,$cardno,Port::ERROR,'接口错误');
                throw new ErrorException('接口错误');
            }

        }else{
            self::idcardLog($uid,$name,$cardno,Port::ERROR,'身份证格式错误');
            throw new ErrorException('身份证格式错误');
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
            //throw new ErrorException('身份证格式错误');
        }
    }


    /**
     *发送手机验证码
     * @param $phone  手机号
     * @param $templateid 短信模板编码
     * @param $money 提现的钱数（提现的时候使用）
     * @return mixed
     * @throws ErrorException
     */
    public static function ValidatePhone($phone,$templateid = '2001',$money = ''){
        $phone = (string)$phone;
        $time = 2;
        $code = self::verification(6);
        //20秒发送一次验证码
        $time_send = time()-20;
        $count = VerifyCode::find()
            ->andWhere([
                'type' => 1,
                'field' => $phone,
                'status' => self::CONFIRM
            ])->orderBy('b_time desc')->one();
        if($time_send < $count['b_time']){
            throw new ErrorException('请勿重复点击');
        }
            $param = $code;
            $b_time = time();
            $res = self::sendSms($phone,$param,$templateid);
            if(!$res->errcode){
                //发送成功
                $sms = new VerifyCode();
                $sms->code = $code;
                $sms->field = $phone;
                $sms->type = '1';
                $sms->b_time = $b_time;
                $sms->e_time = $b_time + $time*60;
                $sms->status = Port::CONFIRM;
                $flag = $sms->save();
                if($flag){
                    return true;
                }else{
                    self::phongLog($phone,$code,Port::ERROR,'数据写入失败');
                    throw new ErrorException('数据写入失败');
                }
            }else{
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
                throw new ErrorException($errmsg);
            }

    }

    /**
     *发送手机验证码
     * @param $phone  手机号
     * @param $templateid 短信模板编码
     * @param $money 提现的钱数（提现的时候使用--模板2）
     * @return mixed
     * @throws ErrorException
     */
    public static function ValidatePhone2($phone,$templateid,$money = ''){
        $phone = (string)$phone;
        //短信有效时间
        $vtime = 2;
        $code = self::verification(6);
        $b_time = time();
        if($money != "" && $templateid == '2'){
            $code = $money;
        }
        //20秒发送一次验证码
        $time = time()-20;
        $count = VerifyCode::find()
            ->andWhere([
                'type' => 1,
                'field' => $phone,
                'status' => self::CONFIRM
            ])->orderBy('b_time desc')->one();
        if($time < $count['b_time']){
            throw new ErrorException('请勿重复点击');
        }
        $res = self::sendSms2($phone,$code,$vtime,$templateid);
        if(!$res['errorNum']){
            //发送成功
            $sms = new VerifyCode();
            $sms->code = $code;
            $sms->field = $phone;
            $sms->type = '1';
            $sms->b_time = $b_time;
            $sms->e_time = $b_time + $vtime*60;
            $sms->status = Port::CONFIRM;
            $flag = $sms->save();
            if($flag){
                return true;
            }else{
                self::phongLog($phone,$code,Port::ERROR,'数据写入失败');
                throw new ErrorException('数据写入失败');
            }
        }elseif($res['errorNum'] == '2'){
            $errmsg = $res['errorMsg'];
            self::phongLog($phone,$code,Port::ERROR,$errmsg);
            throw new ErrorException($errmsg);
        }else{
            $errmsg = $res['errorMsg'];
            self::phongLog($phone,$code,Port::ERROR,$errmsg);
            throw new ErrorException('发送短信失败');
        }
    }

    /**
     * 验证手机验证码
     * @param $phone 手机号
     * @param $code 验证码
     * @return mixed
     * @throws ErrorException
     */
    public static function checkPhnoe($phone,$code){
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
                      return true;
                  }else{
                      self::phongLog($phone,$code,Port::CONFIRM,'发送成功，数据保存异常');
                      throw new ErrorException("数据保存异常");
                  }
              }else{
//                  self::phongLog($phone,$code,Port::CONFIRM,'发送成功，验证码错误');
                  throw new ErrorException("验证码错误");
              }
          }else{
              self::phongLog($phone,$code,Port::ERROR,'发送成功，验证码超时');
              throw new ErrorException('验证码超时');
          }
      }else{
          throw new ErrorException("请重新获取");
      }

    }

    /**
     * 短信接口
     * @param $phone
     * @param $param 短信模板参数列表
     * @param $templateid
     * @return mixed
     */
    public static function sendSms($phone,$param,$templateid){

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
     * 短信接口
     * @param $phone
     * @param $param 短信模板参数列表
     * @param $templateid
     * @return mixed
     */
    public static function sendSmsall($phone,$templateid){

        $appkey="eddcee3a76d0e5e267044bafbc5b393a";
        $ch = curl_init();
        $url = "http://api.id98.cn/api/sms?appkey=".$appkey."&phone=".$phone."&templateid=".$templateid;
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
     * 新短信发送接口
     * @param $mobile 手机号
     * @param $verify 验证码
     * @param $verify_time 有效时间
     * @param $templateid 模板编码
     * @return array|bool
     */
    public static function sendSms2($mobile,$verify,$verify_time,$templateid){
        //获取时间
        date_default_timezone_set('prc');
        $datetime = date('y-m-d H:i:s',time());
        $time = strtotime($datetime);
        $send_time = date('y年m月d日 H:i',$time);
        //判定模板编码的正确性
        $templatea_array = array('1','2','3','4','5');
        if(!in_array($templateid,$templatea_array))
        {
            $return = array(
                'errorNum' => '2',
                'errorMsg' => '短信模板错误',
                'data' => null
            );
            return $return;
        }
        //定义短信模板---1，注册 2，购买 3，提现，4，重置密码，5，修改密码
        if($templateid == '1'){
            $template = '【理财王】注册校验码：'.$verify.',有效时间'.$verify_time.'分钟，感谢注册理财王，如需帮助请致电：4006 985 185.';
        }elseif($templateid == '2'){
            $template = '【理财王】尊敬的投资者，您已成功购买'.$verify.'元理财王理财，即日起开始计息！';
        }elseif($templateid == '3'){
            $template = '【理财王】尊敬的投资者，您于'.$send_time.' 发起一笔提现申请，请求已处理，资金会在24小时内到账，敬请留意';
        }elseif($templateid == '4'){
            $template = '【理财王】尊敬的投资者，您于'.$send_time.'进行了密码重置操作，短信校验码为'.$verify.',有效时间'.$verify_time.'分钟，如非本人操作，请忽略';
        }elseif($templateid == '5'){
            $template = '【理财王】尊敬的投资者，您于'.$send_time.'进行了修改密码操作，短信校验码为'.$verify.',有效时间'.$verify_time.'分钟，如非本人操作，请忽略';
        }elseif($templateid == '6'){
            $template = '';
        }
        //667857
        $data = array(
            'name'=>'18600891723',     //必填参数。用户账号
            'pwd'=>'A6B2CE5DE10A41408DF3FF1A297B',     //必填参数。（web平台：基本资料中的接口密码）
            'content'=>$template,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'mobile'=>$mobile,   //必填参数。手机号码。多个以英文逗号隔开
            'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'=>'',    //必填参数。用户签名。
            'type'=>'pt',  //必填参数。固定值 pt
            'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
        );
        $url = 'http://sms.1xinxi.cn/asmx/smsservice.aspx';
        $url = trim($url);
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);
        //释放curl句柄
        curl_close($ch);
        if(0 !== $errorCode) {
            return false;
        }
        $status = substr($output, 0, 1 );  //获取信息发送后的状态
        if(!$status){
            $return = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null
            );
            return $return;
        }else{
            $return = array(
                'errorNum' => '1',
                'errorMsg' => $output,
                'data' => null
            );
            return $return;
        }
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