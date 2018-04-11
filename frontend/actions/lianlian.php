<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/8/6
 * Time: 10:19
 */

namespace frontend\actions;


use common\models\base\asset\Info;
use common\models\lianlian\payLL;
use framework\lianlian\lianlianClass;
use yii\base\Component;
use Yii;
class lianlian  extends Component{
    const CONFIRM = -1;
    const ERROR = 0;
    const SUCCESS = 1;

    /**
     * 用户首次支付
     * @param $uid 用户id
     * @param $card_no 银行卡号
     * @param $money 充值金额
     */
    public static function confirmation($uid,$card_no,$money){
        $flag = member::isAuthentic($uid);
        if($flag){
            $id_cardno = $flag['idcard'];
            $real_name = $flag['real_name'];
            //TODO 获取银行卡信息
            $bank_code = '01050000';


            //用户标识
            $flag = payLL::find()->where(
                [
                    'status' => self::SUCCESS,
                    'uid' => $uid
                ])
                ->orderBy('create_at desc')
                ->one();
            if($flag){
                $user_id = $flag['user_id'];
            }else{
                $user_id = self::verification($uid);
            }
            //商户业务类型
            $busi_partne = '101001';
            //商户唯一订单号
            $no_order = self::verification($card_no);
            $name_goods = "充值";
            $money_order = $money;

            $notify_url = 'http://www.lianlianpay.com/notify_url.html';
            $id_no = $id_cardno;
            $acct_name = $real_name;
            $card_no = $card_no;
            //写入数据库记录
            $pay = new payLL();
            $pay->uid = $uid;
            $pay->idcard = $id_cardno;
            $pay->real_name = $real_name;
            $pay->user_id = $user_id;
            $pay->busi_partne = $busi_partne;
            $pay->no_order = $no_order;
            $pay->name_goods = $name_goods;
            $pay->money_order = $money;
            $pay->card_no = $card_no;
            $pay->from_ip = Yii::$app->request->userIp;
            $pay->status = self::CONFIRM;
            $res = $pay->save();
            if($res){
                $lianlian = new lianlianClass();
                $res = $lianlian->confirmation($user_id,$busi_partne,$no_order,$name_goods,$money_order,$notify_url,$id_no,$acct_name,$card_no,$bank_code);
                return $res;
            }

        }else{
            return "先实名认证";
        }
    }

    /**
     * 获取用户绑卡信息
     * @param $uid
     * @return bool
     */
    public static function getBankcard($uid){
        $info = Info::findOne($uid);
        if($info){
            $bank_card = $info->bank_card;
            $bank_code = $info->bank_card_phone;
            if($bank_card != "" && $bank_code != ""){
                $bank = array(
                    '01020000' => "工商银行",
                    '01030000' => '农业银行',
                    '01040000' => '中国银行',
                    '01050000' => '建设银行',
                    '03080000' => '招商银行',
                    '03100000' => '浦发银行',
                    '03030000' => '光大银行',
                    '03070000' => '平安银行',
                    '03040000' => '华夏银行',
                    '03090000' => '兴业银行',
                    '03020000' => '中信银行',
                    '01000000' => '储蓄银行',
                    '03050000' => '民生银行',
                    '03060000' => '广发银行'
                );
                $bank_name = $bank[$bank_code];
                $data['bank_card'] = $bank_card;
                $data['bank_name'] = $bank_name;
                return $data;
            }else{
                return false;
            }

        }else{
            return false;

        }

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
        return md5(microtime().$str.$type.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT));
    }






}