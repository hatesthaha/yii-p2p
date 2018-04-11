<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/27
 * Time: 16:39
 */
namespace frontend\actions;


use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\base\site\IdcardLog;
use common\models\UcenterMember;
use framework\base\ErrorException;
use Yii;
class member extends Action
{
    const  SUCCEED  = 1;


    /**
     * 判定用户手机号是否存在
     * @param $phone
     * @return bool
     */
    public static function phoneIsRegister($phone){
        $res = UcenterMember::findOne([
           'phone' => $phone
        ]);
        if($res){
            return $res['id'];
        }else{
            return false;
        }
    }
    /**
     * 生成随机不重复八位邀请码
     * @return string
     */
    public static function random(){
        $random = substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $random;
    }


    /**用户实名认证接口
     * @param $uid 用户id
     * @param $name 实名信息
     * @param $cardno 身份证件号
     * @return bool
     * @throws ErrorException
     */
    public static function authentication($uid,$name,$cardno)
    {

        $is = self::isAuthentic($uid);
        $iscard = self::idcardIsAuthentic($cardno);
        if($is || $iscard){
            throw new ErrorException('已经存在实名认证信息');
        }else{
            //调用验证接口
            // $res = Port::authentication($uid,$name,$cardno);
            //$res = Port::baiduIdentity($cardno);
            $res = sinapay::authentication($uid,$name,$cardno);
            if(!$res['errorNum']){
                $member = UcenterMember::findOne($uid);
                $member->idcard = $cardno;
                $member->real_name = $name;
                $member->status = UcenterMember::STATUS_REAL;
                $flag = $member->save();
                if($flag){
                    return true;
                }else{
                    throw new ErrorException('实名信息存储失败');
                }
            }else{
                throw new ErrorException($res['errorMsg']);
            }
        }
    }

    /**
     * 判定用户是否实名认证
     * @param $uid
     * @return bool
     */
    public static function isAuthentic($uid){
        $member = UcenterMember::findOne($uid);
        if($member){
            if($member->idcard && $member->real_name){
                $return['real_name'] = $member->real_name;
                $return['idcard'] = $member->idcard;
                return $return;
            }
        }
       else{
            return false;
        }
    }

    /**判定身份证是否已认证
     * @param $idcard
     * @return bool
     */
    public static function idcardIsAuthentic($idcard){
        $member = UcenterMember::findOne([
            'idcard' => $idcard,
        ]);
        if($member){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取邀请人列表
     * @param $uid
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public static function getInvite($uid){
        $invite = UcenterMember::find()->select(['real_name','phone','created_at'])->where([
            'invitation_id' => $uid,
            'status' => UcenterMember::STATUS_REAL
        ]);
        if($invite){
            return $invite;
        }else{
            return false;
        }
    }


    /**
     * 获取用户今日充值次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_deposit_num($uid){
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_RECHAR_SUC
        ])->andWhere(['between','create_at',$zero_time,$tom_zero_time])->count();
        if($log){
            return $log;
        }else{
            return false;
        }
    }
    /**
     * 获取用户今日投资次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_invest_num($uid){
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_INVEST_SUC
        ])->andWhere(['between','create_at',$zero_time,$tom_zero_time])->count();
        if($log){
            return $log;
        }else{
            return false;
        }
    }
    /**
     * 获取用户今日赎回次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_withdraw_num($uid){
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_REDEM_SUC
        ])->andWhere(['between','create_at',$zero_time,$tom_zero_time])->count();
        if($log){
            return $log;
        }else{
            return false;
        }
    }
    /**
     * 获取用户今日提现次数
     * @param $uid
     * @return bool|int|string
     */
    public static function get_ransom_num($uid){
        //今日零时时间
        $zero_time = strtotime(date("Y-m-d"));
        //明日零时时间
        $tom_zero_time = $zero_time + 86400;
        $log = \common\models\base\asset\Log::find()->where([
            'member_id' => $uid,
            'status' => \common\models\base\asset\Log::STATUS_WITHDRAW_SUC
        ])->andWhere(['between','create_at',$zero_time,$tom_zero_time])->count();
        if($log){
            return $log;
        }else{
            return false;
        }
    }

    /**
     * 获取用户注册手机号
     * @param $uid
     * @return bool|mixed
     */
    public static function getPhone($uid){
    $user = UcenterMember::find()->where([
        'id' => $uid,
        'lock' => UcenterMember::TYPE_UNLOCK
    ])->asArray()->one();
        if($user){
            return $user['phone'];
        }else{
            return false;
        }

    }

    /**
     * 给予体验金
     * @param $title
     * @param $uid
     * @return bool
     */
    public static function give_experience_gold($title,$uid){
        //获取体验金规则
        $rule = Rule::find()->where(['title'=>$title,'status'=>Rule::STATUS_ACTIVE])->one();
        if($rule){
            $rid = $rule->id;
            $r_money = $rule->money;
            //加入体验金记录表
            $model_gold = new Gold();;
            $model_gold->rid = $rid;
            $model_gold->money = $r_money;
            $model_gold->uid = $uid;
            return $model_gold->save();
        }
    }

}