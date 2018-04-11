<?php
/**
 * Created by PhpStorm.
 * Auther: langxi
 * Date: 2015/7/20
 * Time: 11:00
 * 一些单独使用的方法
 */
namespace frontend\actions\App;

use backend\models\Article;
use backend\models\Category;
use common\models\base\activity\Card;
use common\models\base\activity\Code;
use common\models\base\activity\RaiseCard;
use common\models\base\asset\Info;
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\cms\Lunbo;
use common\models\cms\ReadingLog;
use common\models\post\SendMsgAll;
use yii\base\ErrorException;
use common\models\UcenterMember;
use common\models\base\setting\Setting;
use frontend\actions\Action;
use common\models\base\fund\Income;
use common\models\base\asset\Log;
use common\models\base\cms\Feedback;

class AloneMethod extends Action
{
    /**
     * Auther:langxi
     *
     * 用户输入邀请码获取加息劵，需要改变activity_code的状态值，1为被领取,0为未领取
     */
    public static function getCoupon($member_id, $name)
    {
        if (!$member_id || !is_numeric($member_id) || !is_int($member_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $name = Code::find()->where(['name' => $name, 'status' => '0'])->asArray()->one();
        if (!$name) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '邀请码不存在或已被使用',
                'data' => null,
            );
            return $result;
        }

        $id = $name['id'];  //邀请码对应的加息劵id（code表的id）
        $coupon_id = $name['coupon_id'];  //加息劵id
        //$validity_time = $name['validity_time']; //加息劵作用时间长度
        $rate = $name['rate'];  //利率
        $use_at = $name['use_at'];  //有效期时间起点
        $use_end_time = $name['use_end_time'];  //有效期时间结束点

        //事物回滚
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //将邀请码获取到的加息劵放入到用户的加息劵中
            $raise_card = new RaiseCard();
            $raise_card->member_id = $member_id;
            $raise_card->coupon_id = $coupon_id;
            $raise_card->validity_start_at = $use_at;
            $raise_card->validity_out_at = $use_end_time;
            $raise_card->rate = $rate;
            $raise_card = $raise_card->save();
            if (!$raise_card) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '将邀请码获取到的加息劵放入到用户的加息劵中失败',
                    'data' => null,
                );
                return $result;
            }

            //改变邀请码对应的加息劵的状态为被领取
            $code = Code::findOne($id);
            $code->status = '1';
            $code = $code->save();
            if (!$code) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '改变邀请码状态失败',
                    'data' => null,
                );
                return $result;
            }

            $transaction->commit();
            $result = array('errorNum' => '0', 'errorMsg' => 'success', 'data' => null);
            return $result;
        } catch (ErrorException $e) {
            $transaction->rollBack();
            $remark = $e->getMessage();
            $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
            return $result;
        }

    }


    /**
     * Auther:langxi
     * $member_id：用户id、$coupon_id：加息劵id
     * （仅）用户使用加息劵,使用时间，结束时间，能否使用加息劵判断。
     */
    public static function useRaise($member_id, $coupon_id)
    {
        if (!$member_id || !is_numeric($member_id) || !is_int($member_id) || !$coupon_id || !is_numeric($coupon_id) || !is_int($coupon_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $is_user = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        if (!$is_user) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $is_coupon = RaiseCard::find()->where(['id' => $coupon_id, 'member_id' => $member_id])->asArray()->one();
        if (!$is_coupon) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }

        //判断加息是否达到每日加息最大值，若达到则提示用户，加息劵不可使用
        $Raise = (new \yii\db\Query())
            ->select(['sum(rate)'])
            ->from(['activity_raise_card'])
            ->where(['member_id' => $member_id, 'status' => 1])
            ->createCommand();
        $TotalRaise = $Raise->queryAll();
        $max_raise = self::getRaise();
        if ($TotalRaise['0']['sum(rate)'] > $max_raise) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '今日加息已达到最大值，请明日再用',
                'data' => null,
            );
            return $result;
        } else {
            $card = Card::find()->where(['id' => $coupon_id])->asArray()->one();
            $validity_time = $card['validity_time'];
            $Raise = RaiseCard::find()->where(['member_id' => $member_id, 'id' => $coupon_id])->asArray()->one();
            $raise_id = $Raise['id'];
            $Raise = RaiseCard::findOne($raise_id);
            $Raise->use_at = time();
            $Raise->use_end_time = time() + $validity_time * 3600 * 24;
            $Raise->status = '1';
            $Raise = $Raise->save();
            if (!$Raise) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '加息劵使用失败',
                    'data' => null,
                );
                return $result;
            } else {
                $result = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null,
                );
                return $result;
            }
        }

    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日最大加息利率(Setting表存放参数)
     * @return string
     */
    private static function getRaise()
    {
        $result = Setting::find()->where(['code' => 'rate'])->asArray()->one();
        if ($result) {
            $result = $result['value'];
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取允许用户每日的最大加息利率失败',
                'data' => null,
            );
            return $result;
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 获取用户的再投金额
     */
    public static function online_invest($member_id)
    {
        $result = (new \yii\db\Query())
            ->select(['money'])
            ->from('fund_orders')
            ->where(['member_id' => $member_id])
            ->sum('money');
        if (!$result) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('money' => $result),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取用户的再投金额失败',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * auther:langxi
     *
     * 获取用户的累计投资额
     */
    public static function ut_invest($member_id)
    {
        $invest = (new \yii\db\Query())
            ->select(['tatal_invest'])
            ->from('asset_info')
            ->where(['member_id' => $member_id])
            ->one();
        if ($invest) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('total_invest' => $invest['tatal_invest']),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取允许用户每日的最大加息利率失败',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * auther:langxi
     *
     * 获取网站总投资数据字典
     */
    public static function see_total()
    {
        $common = (new \yii\db\Query())
            ->select(['invest_sum', 'invest_people', 'invest_times'])
            ->from('fund_common')
            ->one();
        if ($common) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('invest_sum' => $common['invest_sum'], 'invest_people' => $common['invest_people'], 'invest_times' => $common['invest_times']),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '总记录表中数据，请添加',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * auther:langxi
     *
     * 获取用户可用收益
     */
    public static function profit($member_id)
    {
        $profit = (new \yii\db\Query())
            ->select(['profit'])
            ->from('asset_info')
            ->where(['member_id' => $member_id])
            ->one();
        if ($profit) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('profit' => $profit['profit']),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取用户可用收益失败',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * auther:langxi
     *
     * 获取用户累计收益
     */
    public static function total_revenue($member_id)
    {
        $total_revenue = (new \yii\db\Query())
            ->select(['total_revenue'])
            ->from('asset_info')
            ->where(['member_id' => $member_id])
            ->one();
        if ($total_revenue) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('total_revenue' => $total_revenue['total_revenue']),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取用户累计收益失败',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 获取用户昨日收益
     */
    public static function yday_profit($member_id)
    {
        $profit = Income::find()->where(['member_id' => $member_id])->orderBy('id desc')->asArray()->one();
        if ($profit) {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('smoney' => $profit['smoney']),
            );
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取昨日收益失败',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 获取用户昨日收益，再投金额，再投收益，账户余额集合
     */
    public static function user_collect($member_id)
    {
        $is_m = (new \yii\db\Query())
            ->select(['member_id'])
            ->from('asset_info')
            ->where(['member_id' => $member_id])
            ->one();
        if (!$is_m) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该用户不存在',
                'data' => null,
            );
        } else {
            //昨日收益
            $yday_profit = Income::find()->where(['member_id' => $member_id])->orderBy('id desc')->asArray()->one();
            if (!$yday_profit) {
                $yday_profit = 0.00;
            } else {
                $yday_profit = $yday_profit['smoney'];//昨日收益
            }

            //再投金额
            $online_invest = (new \yii\db\Query())
                ->select(['money'])
                ->from('fund_orders')
                ->where(['member_id' => $member_id])
                ->sum('money');
            if (!$online_invest) {
                $online_invest = 0.00;
            }

            //再投收益 账户余额
            $info = (new \yii\db\Query())
                ->select(['profit', 'balance'])
                ->from('asset_info')
                ->where(['member_id' => $member_id])
                ->one();
            if (!$info) {
                $profit = 0.00;
                $balance = 0.00;
            } else {
                $profit = $info['profit'];
                $balance = $info['balance'];
            }

            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('yday_profit' => $yday_profit, 'online_invest' => $online_invest, 'profit' => $profit, 'balance' => $balance),
            );
        }

        return $result;
    }

    /**
     * Auther:langxi
     *
     * 收益记录
     */
    public static function profit_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $profit_log = Income::find()->select(['smoney','created_at'])->where(['member_id'=>$member_id])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($profit_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$profit_log),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无收益记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 充值记录
     */
    public static function recharge_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $info_log = Log::find()->select(['step','remark','create_at','status'])->where(['member_id'=>$member_id,'status'=>Log::STATUS_RECHAR_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();

        if($info_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$info_log),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无充值记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 投资记录
     */
    public static function invest_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $info_log = Log::find()->select(['step','remark','create_at','status'])->where(['member_id'=>$member_id,'status'=>Log::STATUS_INVEST_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($info_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$info_log),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无投资记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     *提现记录
     */
    public static function withdrawals_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $info_log = Log::find()->select(['step','remark','create_at','status'])->where(['member_id'=>$member_id,'status'=>Log::STATUS_WITHDRAW_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($info_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$info_log),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无提现记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     *赎回记录
     */
    public static function redeem_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $info_log = Log::find()->select(['step','remark','create_at','status'])->where(['member_id'=>$member_id,'status'=>Log::STATUS_REDEM_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($info_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$info_log),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无赎回记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 交易记录（充值，投资，提现，赎回）
     */
    public static function total_log($member_id,$page,$num){
        $page = ($page-1)*$num;
        $info_log = Log::find()->select(['step','remark','status','create_at'])->where(['member_id'=>$member_id])->andWhere(['>','status','0'])->andWhere(['<>','step','0.00'])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        $info_log_count = Log::find()->where(['member_id'=>$member_id])->andWhere(['>','status','0'])->andWhere(['<>','step','0.00'])->count();
        if($info_log){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$info_log),
            );
        }elseif(!$info_log && $info_log_count){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '没有更多交易记录',
                'data' => null,
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无交易记录',
                'data' => null,
            );
        }
        return $result;
    }

    /**
     * 获取用户体验金列表
     * @param $uid
     * @param string $page
     * @param string $num
     * @return array
     */
    public  static  function  experience_gold_log($uid,$page = '1',$num = '5'){
        //获取体验金列表
        $page = ($page-1)*$num;
        $list = Gold::find()->select(['money','created_at','end_at','status','rid','title'])->where(['uid' => $uid])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        //获取用户总共数目
        $total_count = Gold::find()->where(['uid' => $uid])->count();
        //可用体验金的条数
        $available_count = Gold::find()->where(['uid' => $uid,'status' => Gold::STATUS_ACTIVE])->andWhere(['<','created_at',time()])->andWhere(['>','end_at',time()])->count();
        //可用体验金的总金额
        $available_sum = Gold::find()->where(['uid' => $uid,'status' => Gold::STATUS_ACTIVE])->andWhere(['<','created_at',time()])->andWhere(['>','end_at',time()])->sum('money');
        if(!$list && !$total_count){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '无记录',
                'data' => null,
            );
            return $result;
        }elseif(!$list && $total_count){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无更多记录',
                'data' => null,
            );
            return $result;
        }else{
            foreach($list as $key=>$value){
//                $rule = Rule::find()->where(['id' => $value['rid']])->asArray()->one();
//                if($rule){
//                    unset($list[$key]['rid']);
//                    $list[$key]['from'] = $rule['title'];
//                }else{
//                    $list[$key]['from'] = '';
//                }
                $list[$key]['from'] = $list[$key]['title'] == null ? '':$list[$key]['title'];
                if($value['end_at'] < time()){
                    $list[$key]['status'] = (string)Gold::STATUS_DELETED;
                }
                $list[$key]['money'] = sprintf("%.2f", $value['money']);
            }
            $available_sum = $available_sum ? sprintf('%.2f',$available_sum) : '0';
            $data = array('list' => $list,'total_count' => $total_count,'available_count' => $available_count,'available_sum'=>$available_sum);
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data,
            );
            return $result;
        }



    }


    /**
     * Auther:langxi
     *
     * 消息中心
     */
    public static function m_centor($page,$num){
        $category = Category::find()->where(['id'=>'49','status'=>Category::STATUS_ACTIVE])->one();
        if(!$category){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '消息显示异常请联系管理员',
                'data' => null,
            );
            return $result;
        }
        $page = ($page-1)*$num;
        $article = Article::find()->select(['id','title','intro','content','logo','create_at'])->where(['category_id'=>'49','status'=>Article::STATUS_ACTIVE])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($article){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$article),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取失败或暂无消息',
                'data' => null,
            );
        }
        return $result;
    }


    /**
     * Auther:langxi
     *
     * 反馈意见
     */
    public static function feedback($uid,$content){
        $feedback = new Feedback();
        $feedback->uid = $uid;
        $feedback->feedback = $content;
        $feedback = $feedback->save();
        if($feedback){
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null,
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '反馈意见失败',
                'data' => null,
            );
        }
        return $result;
    }
    /**
     * Auther:langxi
     *
     * 消息中心
     */
    public static function user_msg_centor($uid,$page,$num){
        $category = Category::find()->where(['id'=>'49','status'=>Category::STATUS_ACTIVE])->one();
        if(!$category){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '消息显示异常请联系管理员',
                'data' => null,
            );
            return $result;
        }
        $page = ($page-1)*$num;
        $article = Article::find()->select(['id','title','intro','content','logo','create_at'])->where(['category_id'=>'49','status'=>Article::STATUS_ACTIVE])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if($article){
            foreach($article as $key => $value){
                //阅读标识
                $article[$key]['is_read'] = '0';
                $aid = $value['id'];
                $flag = ReadingLog::find()->where([
                    'uid' => $uid,
                    'aid' => $aid
                ])->one();
                if($flag){
                    //存在阅读记录--更改阅读状态
                    $article[$key]['is_read'] = '1';
                }
            }
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('data'=>$article),
            );
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取失败或暂无消息',
                'data' => null,
            );
        }
        return $result;
    }
    /**
     * 判定用户是否有未读消息
     * @param $uid
     * @return array
     */
    public static function is_exit_msg($uid){
        $all_num = Article::find()->select(['id'])->where(['category_id'=>'49','status'=>Article::STATUS_ACTIVE])->asArray()->all();
        $msg_arr = array();
        if($all_num){
            foreach($all_num as $key => $value){
                $msg_arr[$key] = $value['id'];
            }
        }
        return self::is_msg($msg_arr,$uid);
    }

    /**
     * 用户阅读文章
     * @param $aid
     * @param $uid
     */
    public static function reading_log($aid,$uid){
        $flag = self::is_reading($aid,$uid);
        if($flag['errorNum']){
            $log = new ReadingLog();
            $log->aid = $aid;
            $log->uid = $uid;
            $log->status = ReadingLog::STATUS_ACTIVE;
            if($log->save()){
                $result = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null,
                );
                return $result;
            }else{
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => 'db error',
                    'data' => null,
                );
                return $result;
            }
        }
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => null,
        );
        return $result;
    }

    /**
     * 判定用户是否阅读
     * @param $aid
     * @param $uid
     * @return array
     */
    public static function is_reading($aid,$uid){
        $flag = ReadingLog::find()->where([
            'uid' => $uid,
            'aid' => $aid
        ])->one();
        if($flag){
            //已经阅读
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null,
            );
            return $result;
        }else{
            //还未阅读
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '还未阅读',
                'data' => null,
            );
            return $result;
        }
    }

    /**
     *用户获取消息条目
     * @param $msa_arr
     * @param $uid
     * @return array
     */
    public static function is_msg($msa_arr,$uid){
        if(!is_array($msa_arr)){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '请以数据形式传参',
                'data' => null,
            );
            return $result;
        }
        $count = 0;
        $arr_length = count($msa_arr);
        if($arr_length){
            foreach($msa_arr as $key=>$value){
                $flag = self::is_reading($value,$uid);
                if($flag['errorNum']){
                    $count ++;
                }
            }
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => array('count' => $count),
            );
            return $result;

        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '空数组',
                'data' => null,
            );
            return $result;
        }
    }

    /**
     * 忽略全部消息
     * @param $msa_arr
     * @param $uid
     * @return array
     */
    public static function ignore_all($msa_arr,$uid){
        if(!is_array($msa_arr)){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '请以数据形式传参',
                'data' => null,
            );
            return $result;
        }
        $arr_length = count($msa_arr);
        if($arr_length){
            foreach($msa_arr as $key=>$value){
                $flag = ReadingLog::find()->where([
                    'uid' => $uid,
                    'aid' => $value
                ])->one();
                if(!$flag){
                    //如果不存在阅读记录
                    $log = new ReadingLog();
                    $log->aid = $value;
                    $log->uid = $uid;
                    $log->status = ReadingLog::STATUS_ACTIVE;
                    $log->save();
                }
                continue;
            }
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null,
            );
            return $result;
        }else{
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => null,
            );
            return $result;
        }
    }

    /**
     * 获取轮播图
     * @return array
     */
    public static function cms_lunbo(){
        $lunbo = Lunbo::find()->select(['title','url','order','info','content','event_link','share_link'])->where(['status' => Lunbo::STATUS_SUCCESS,'type' => Lunbo::TYPE_LUNBO])->orderBy('order desc,id desc')->asArray()->all();
        if(!empty($lunbo)){
            $data = array('list' => $lunbo);
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data,
            );
            return $result;
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无图片',
                'data' => null,
            );
            return $result;
        }

    }

    /**
     * 获取启动页
     * @return array
     */
    public static function cms_qidong(){
        $lunbo = Lunbo::find()->select(['title','url','order','info','content','event_link'])->where(['status' => Lunbo::STATUS_SUCCESS,'type'=>Lunbo::TYPE_QIDONG])->orderBy('order desc,id desc')->asArray()->all();
        if(!empty($lunbo)){
            $data = array('list' => $lunbo);
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $data,
            );
            return $result;
        }else{
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无图片',
                'data' => null,
            );
            return $result;
        }

    }

    /**
     * 加密算法
     * @param $data
     * @return string
     */
    public static function encrypt($data)
    {
        $key = 'hqw123456';
        $key	=	md5($key);
        $x		=	0;
        $len	=	strlen($data);
        $l		=	strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密算法
     * @param $data
     * @return string
     */
    public static function decrypt($data)
    {
        $key = 'hqw123456';
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    public static function send_msg_all($templateid){
        //获取所有用户信息
        $users = UcenterMember::find()->asArray()->all();
        if($users){
            //循环用户手机号
            foreach($users as $key=>$value){
                $log = new SendMsgAll();
                $log->phone = $value['phone'];
                $log->templateid = $templateid;
                $log->status = '';
                $log->save();
                var_dump($value['phone']);
            }
        }
    }




}