<?php
/**
 * Created by PhpStorm.
 * Auther: langxi
 * Date: 2015/7/20
 * Time: 11:00
 * 一些单独使用的方法
 */
namespace frontend\actions;

use common\models\base\activity\ActivityLog;
use common\models\base\activity\Card;
use common\models\base\activity\Code;
use common\models\base\activity\RaiseCard;
use common\models\base\activity\UserRecommend;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\cms\Feedback;
use common\models\base\fund\Common;
use common\models\base\fund\Income;
use common\models\base\asset\TradeLog;
use common\models\base\asset\TaskLog;
use framework\helpers\Utils;
use frontend\actions\app\member;
use yii\base\ErrorException;
use common\models\UcenterMember;
use common\models\base\setting\Setting;
use backend\models\Category;
use backend\models\Article;
use yii\base\Exception;

class AloneMethod extends Action
{
    /**
     * Auther:langxi
     *
     * 用户输入邀请码获取加息劵，需要改变activity_code的状态值，1为被领取,0为未领取
     */
    public static function getCoupon($member_id, $name)
    {
        $name = Code::find()->where(['name' => $name, 'status' => '0'])->asArray()->one();
        if (!$name) {
            throw new ErrorException('邀请码不存在或已被使用', 7001);
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
                throw new ErrorException('将邀请码获取到的加息劵放入到用户的加息劵中失败', 7001);
            }

            //改变邀请码对应的加息劵的状态为被领取
            $code = Code::findOne($id);
            $code->status = '1';
            $code = $code->save();
            if (!$code) throw new ErrorException('改变邀请码状态失败', 7001);

            $transaction->commit();
            return true;
        } catch (ErrorException $e) {
            $transaction->rollBack();
        }

    }


    /**
     * Auther:langxi
     * $member_id：用户id、$coupon_id：加息劵id
     * （仅）用户使用加息劵,使用时间，结束时间，能否使用加息劵判断。
     */
    public static function useRaise($member_id, $coupon_id)
    {
        //判断加息是否达到每日加息最大值，若达到则提示用户，加息劵不可使用
        $Raise = (new \yii\db\Query())
            ->select(['sum(rate)'])
            ->from(['activity_raise_card'])
            ->where(['member_id' => $member_id, 'status' => 1])
            ->createCommand();
        $TotalRaise = $Raise->queryAll();
        $max_raise = self::getRaise();
        if ($TotalRaise['0']['sum(rate)'] > $max_raise) {
            throw new ErrorException('今日加息已达到最大值，请明日再用', 7001);
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
            if (!$Raise) throw new ErrorException('加息劵使用失败', 5002);
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
            throw new ErrorException('获取允许用户每日的最大加息利率失败', 1004);
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
            throw new ErrorException('获取用户的再投金额失败', 1004);
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
        $total_invest = $invest['tatal_invest'];
        return $total_invest;
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
        if (!$common) {
            throw new ErrorException('总记录表中午数据，请添加', 1004);
        } else {
            $common = array('invest_sum' => $common['invest_sum'], 'invest_people' => $common['invest_people'], 'invest_times' => $common['invest_times']);
        }
        return $common;
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
        if (!$profit) {
            throw new ErrorException('获取用户可用收益失败', 1004);
        }
        $profit = $profit['profit'];
        return $profit;
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
        if (!$total_revenue) {
            throw new ErrorException('获取用户累计收益失败', 1004);
        }
        $total_revenue = $total_revenue['total_revenue'];
        return $total_revenue;
    }

    /**
     * Auther:langxi
     *
     * 获取用户昨日收益
     */
    public static function yday_profit($member_id)
    {
        $profit = Income::find()->where(['member_id' => $member_id])->orderBy('id desc')->asArray()->one();
        if (!$profit) {
            throw new ErrorException('获取昨日收益失败', 1004);
        }
        $profit = $profit['smoney'];
        return $profit;
    }

    /**
     * Auther:langxi
     *
     * 获取用户昨日收益，再投金额，再投收益，账户余额集合
     */
    public static function user_collect($member_id)
    {
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

        $result = array('yday_profit' => $yday_profit, 'online_invest' => $online_invest, 'profit' => $profit, 'balance' => $balance);
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 收益记录
     */
    public static function profit_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $profit_log = Income::find()->select(['smoney', 'created_at'])->where(['member_id' => $member_id])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$profit_log) {
            throw new ErrorException('暂无收益记录');
        }
        return $profit_log;
    }

    /**
     * Auther:langxi
     *
     * 充值记录
     */
    public static function recharge_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $info_log = Log::find()->select(['step', 'remark', 'create_at'])->where(['member_id' => $member_id, 'status' => Log::STATUS_RECHAR_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$info_log) {
            throw new ErrorException('暂无充值记录');
        }
        return $info_log;
    }

    /**
     * Auther:langxi
     *
     * 投资记录
     */
    public static function invest_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $info_log = Log::find()->select(['step', 'remark', 'create_at'])->where(['member_id' => $member_id, 'status' => Log::STATUS_INVEST_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$info_log) {
            throw new ErrorException('暂无投资记录');
        }
        return $info_log;
    }

    /**
     * Auther:langxi
     *
     *提现记录
     */
    public static function withdrawals_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $info_log = Log::find()->select(['step', 'remark', 'create_at'])->where(['member_id' => $member_id, 'status' => Log::STATUS_WITHDRAW_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$info_log) {
            throw new ErrorException('暂无提现记录');
        }
        return $info_log;
    }

    /**
     * Auther:langxi
     *
     *赎回记录
     */
    public static function redeem_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $info_log = Log::find()->select(['step', 'remark', 'create_at'])->where(['member_id' => $member_id, 'status' => Log::STATUS_REDEM_SUC])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$info_log) {
            throw new ErrorException('暂无赎回记录');
        }
        return $info_log;
    }

    /**
     * Auther:langxi
     *
     * 交易记录（充值，投资，提现，赎回）
     */
    public static function total_log($member_id, $page, $num)
    {
        $page = ($page - 1) * $num;
        $info_log = Log::find()->select(['step', 'remark', 'status', 'create_at'])->where(['member_id' => $member_id])->andWhere(['>', 'status', '0'])->andWhere(['<>', 'step', '0.00'])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();

        if (!$info_log) {
            throw new Exception('暂无交易记录');
        }
        return $info_log;
    }

    /**
     * Auther:langxi
     *
     * 消息中心
     */
    public static function m_centor($page, $num)
    {
        $category = Category::find()->where(['id' => '49', 'status' => Category::STATUS_ACTIVE])->one();
        if (!$category) {
            throw new ErrorException('消息显示异常请联系管理员');
        }
        $page = ($page - 1) * $num;
        $article = Article::find()->select(['title', 'intro', 'content', 'logo', 'create_at'])->where(['category_id' => '49', 'status' => Article::STATUS_ACTIVE])->orderBy('id desc')->limit($num)->offset($page)->asArray()->all();
        if (!$article) {
            throw new ErrorException('获取失败或暂无消息');
        }
        return $article;
    }

    /**
     * Auther:langxi
     *
     * 反馈意见
     */
    public static function feedback($uid, $content)
    {
        $feedback = new Feedback();
        $feedback->uid = $uid;
        $feedback->feedback = $content;
        $feedback = $feedback->save();
        if (!$feedback) {
            throw new ErrorException('反馈意见失败');
        }
        return true;
    }

    /*
     * Auther:langxi
     *
     * 网站每日各项总金额记录（充值，投资，赎回，提现，在投金额收益，体验金收益，发放的红包）
     */
    public static function trade_log()
    {

        $t_date = date('Y-m-d');
        $days = 20;
        while ($days-- != 0) {
//            $t_date = date($t_date,'-1 day');

            AloneMethod::trade_date_log($t_date);
            $t_date = date('Y-m-d', strtotime("-1 day", strtotime($t_date)));
        }

    }

    /*
    * Auther:langxi
    *
    * 网站每日各项总金额记录（充值，投资，赎回，提现，在投金额收益，体验金收益，发放的红包）
    */
    public static function trade_date_log($date)
    {
        //判断是否存在当日记录

        $t_date = strtotime($date);

        if (strtotime(date('Y-m-d')) == $t_date || strtotime(date("Y-m-d", strtotime("-1 day"))) == $t_date) {
            TradeLog::deleteAll('t_date=' . $t_date);
        }

        $is_tradelog = TradeLog::find()->andwhere(['=', 't_date', $t_date])->asArray()->one();

        if (!$is_tradelog) {
            $max_time = strtotime(date('Y-m-d', strtotime("+1 day", strtotime($date))));
            $min_time = strtotime($date);

            //获取昨日总充值金额
            $t_recharge = Log::find()->Where(['status' => Log::STATUS_RECHAR_SUC])->andWhere(['>=', 'create_at', $min_time])->andWhere(['<', 'create_at', $max_time])->sum('step');
            if (empty($t_recharge)) {
                $t_recharge = 0.00;
            }
            //获取昨日总投资金额
            $t_invest = Log::find()->Where(['status' => Log::STATUS_INVEST_SUC])->andWhere(['>=', 'create_at', $min_time])->andWhere(['<', 'create_at', $max_time])->sum('step');
            if (empty($t_invest)) {
                $t_invest = 0.00;
            }
            //获取昨日总赎回金额
            $t_redeem = Log::find()->Where(['status' => Log::STATUS_REDEM_SUC])->andWhere(['>=', 'create_at', $min_time])->andWhere(['<', 'create_at', $max_time])->sum('step');
            if (empty($t_redeem)) {
                $t_redeem = 0.00;
            }
            //获取网站总提现金额
            $t_withdraw = Log::find()->Where(['status' => Log::STATUS_WITHDRAW_SUC])->andWhere(['>=', 'create_at', $min_time])->andWhere(['<', 'create_at', $max_time])->sum('step');
            if (empty($t_withdraw)) {
                $t_withdraw = 0.00;
            }
            //获取网站昨日在投收益金额
            $t_profit = Income::find()->andWhere(['>=', 'created_at', $min_time])->andWhere(['<', 'created_at', $max_time])->sum('iincome');
            if (empty($t_profit)) {
                $t_profit = 0.00;
            }
            //获取网站昨日体验金收益金额
            $t_gold = Income::find()->andWhere(['>=', 'created_at', $min_time])->andWhere(['<', 'created_at', $max_time])->sum('goldincome');
            if (empty($t_gold)) {
                $t_gold = 0.00;
            }
            //获取网站昨日发放红包金额
            $t_red = Income::find()->andWhere(['>=', 'created_at', $min_time])->andWhere(['<', 'created_at', $max_time])->sum('railscard');
            if (empty($t_red)) {
                $t_red = 0.00;
            }

            //将其记录到网站金额汇总表里：
            $tradelog = new TradeLog();
            $tradelog->t_recharge = $t_recharge;
            $tradelog->t_invest = $t_invest;
            $tradelog->t_redeem = $t_redeem;
            $tradelog->t_withdraw = $t_withdraw;
            $tradelog->t_profit = $t_profit;
            $tradelog->t_gold = $t_gold;
            $tradelog->t_red = $t_red;
            $tradelog->t_date = $t_date;
            $tradelog = $tradelog->save();
            if (!$tradelog) {
                $tasklog = new Tasklog();
                $tasklog->remark = date('Y-m-d H:i:s', time()) . '执行计划任务写入网站昨日各项总金额（充值，投资，赎回，投资等昨日总额）记录失败';
                $tasklog->url = 'AloneMethod/trade_log';
                $tasklog->save();
            }
        }
    }

    /**
     * 计算某段时间里，某用户再投金额大于特定值的次数
     * @param $uid
     * @param $money
     * @param $days
     */
    public static function get_continue_money($uid, $money, $days)
    {
        //设定时区
        date_default_timezone_set('PRC');
        //取出当日零时的时间
        $btime = date('Y-m-d' . '00:00:00', time());
        //转换成当前时间戳
        $begintime = strtotime($btime);
        //计算出计算的截止日期
        $endtime = $begintime - $days * 86400;
        //计算出在这个时间段里，大于设定金额的连续投资次数
        $count = Income::find()->where(['member_id' => $uid])->andWhere(['>', 'created_at', $endtime])->andWhere(['>=', 'money', $money])->count();
        //计算当日的再投资金
        $today_invest = 0;
        $invest = Income::find()->where(['member_id' => $uid])->andWhere(['>', 'created_at', $begintime])->one();
        if ($invest !== null) {
            $today_invest = $invest->money;
        }
        $data = array(
            'count' => $count,
            'today_invest' => $today_invest
        );
        return $data;
    }

    /**
     * 获取推荐红包奖励列表
     * @param $uid
     * @return mixed
     */
    public static function get_red_packet_relation($uid)
    {
        //获取直接推荐关系用户
        $son_array = UcenterMember::find()->select(['id', 'phone'])->where(['invitation_id' => $uid, 'type' => UcenterMember::TYPE_UNLOCK])->asArray()->all();
        $son_count = count($son_array);
        //获取一级推荐的推荐关系
        $condition_son = array();
        $phone_son = array();
        if ($son_count != 0) {
            foreach ($son_array as $key => $value) {
                $condition_son[$key] = $value['id'];
                $phone_son[$key] = $value['phone'];
            }
        }
        //第一级推荐---自己推荐用户获取的奖励
        //获取推荐用户获取的红包列表---单独奖励推荐者--来源是‘推广新手’-- TODO
        $actibity_source1 = '推广新手';
        $son_red_list = ActivityLog::find()->select(['phone', 'red_packet', 'actibity_source', 'inviter_draw', 'update_at', 'type'])->where(['in', 'phone', $phone_son])->andWhere(['invite_id' => $uid, 'actibity_source' => $actibity_source1, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER, 'status' => ActivityLog::STATUS_ACTIVITY])->orderBy('id desc')->asArray()->all();
        $son_red_sum = ActivityLog::find()->where(['in', 'phone', $phone_son])->andWhere(['invite_id' => $uid, 'actibity_source' => $actibity_source1, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'status' => ActivityLog::STATUS_ACTIVITY])->sum('red_packet');
        //获取二级推荐红包列表--由用户推荐的用户推荐的用户--自己--子类用户--推荐--新用户--单独奖励推荐者
        //记录这个数据---’推荐大师‘--不包括直接子类推荐的用户
        $actibity_source2 = '推广大师';
        $grandson_red_list = ActivityLog::find()->select(['phone', 'red_packet', 'actibity_source', 'inviter_draw', 'update_at', 'type'])->where(['not in', 'phone', $phone_son])->andWhere(['invite_id' => $uid, 'actibity_source' => $actibity_source2, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER, 'status' => ActivityLog::STATUS_ACTIVITY])->orderBy('id desc')->asArray()->all();
        $grandson_red_sum = ActivityLog::find()->where(['not in', 'phone', $phone_son])->andWhere(['invite_id' => $uid, 'actibity_source' => $actibity_source2, 'type' => ActivityLog::RED_BOTHWAY_TO_INVITER, 'inviter_draw' => ActivityLog::STATUS_INVITER_DRAW_SUCC, 'status' => ActivityLog::STATUS_ACTIVITY])->sum('red_packet');
        $data = array(
            'son_red_list' => $son_red_list,
            'son_red_sum' => $son_red_sum,
            'grandson_red_list' => $grandson_red_list,
            'grandson_red_sum' => $grandson_red_sum
        );
        $response = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $response;
    }

    /**
     * 为用户推荐的用户进行--处理
     * @param $owner_uid
     */
    public static function produce_red_packet($owner_uid)
    {
        //获取直接推荐关系用户--子类已经进行了绑卡操作
        $son_array = UcenterMember::find()->select(['id', 'phone', 'real_name', 'created_at', 'invitation_id'])->where(['invitation_id' => $owner_uid, 'type' => UcenterMember::TYPE_UNLOCK, 'status' => UcenterMember::STATUS_BIND])->asArray()->all();
        $son_count = count($son_array);
        //循环一级推荐关系的用户
        $condition_son = array();
        if ($son_count != 0) {
            // 存在子推荐关系用户
            foreach ($son_array as $key => $value) {
                $condition_son[$key] = $value['id'];
                //判定一级用户是否有资格发送给推荐值红包
                $uid = $value['id'];
                $money = 3000;
                $days = 30;
                $count = self::get_continue_money($uid, $money, $days);
                $flag = UserRecommend::find()->where(['owner_uid' => $owner_uid, 'level' => 1, 'sender_uid' => $uid, 'is_grant' => UserRecommend::GRANT_RECOMMEND])->one();
                if ($count['count'] >= $days) {
                    //有满足’推广新手‘条件的用户--修改数据记录
                    if ($flag !== null) {
                        //更新表数据
                        $flag->demand_money = $count['today_invest'];
                        $flag->demand_days = $count['count'];
                        $flag->red_packet = 30;
                        $flag->red_packet_name = '推广新手';
                        $flag->is_grant = UserRecommend::GRANT_FALSE;
                        $flag->save();
                    }
                } else {
                    if ($flag !== null) {
                        //更新表数据
                        $flag->demand_money = $count['today_invest'];
                        $flag->demand_days = $count['count'];
                        $flag->save();
                    }
                }
            }
        }
        //获取二级推荐关系用户
        $grandson_array = UcenterMember::find()->select(['id', 'phone', 'real_name', 'created_at', 'invitation_id'])->where(['in', 'invitation_id', $condition_son])->andWhere(['type' => UcenterMember::TYPE_UNLOCK])->asArray()->all();
        $grandson_count = count($grandson_array);
        //获取二级推荐的推荐关系
        $condition_grandson = array();
        if ($grandson_count != 0) {
            //存在二级推荐用户
            foreach ($grandson_array as $key1 => $value1) {
                $condition_grandson[$key1] = $value1['id'];
                //判定二级用户是否有资格发送给推荐值红包---推广新手的标准
                $uid1 = $value1['id'];
                $money1 = 3000;
                $days1 = 30;
                $count1 = self::get_continue_money($uid1, $money1, $days1);
                if ($count1['count'] >= $days1) {
                    //有满足’推广新手‘条件的用户--增加数据记录
                    $flag = UserRecommend::find()->where(['owner_uid' => $owner_uid, 'level' => 2, 'sender_uid' => $uid1, 'is_grant' => UserRecommend::GRANT_RECOMMEND])->one();
                    if ($flag !== null) {
                        $flag->demand_money = $count1['today_invest'];
                        $flag->demand_days = $count1['count'];
                        $flag->red_packet = 20;
                        $flag->red_packet_name = '推广大师';
                        $flag->is_grant = UserRecommend::GRANT_FALSE;
                        $flag->save();
                    }
                }else{
                    if ($flag !== null) {
                        //更新表数据
                        $flag->demand_money = $count1['today_invest'];
                        $flag->demand_days = $count1['count'];
                        $flag->save();
                    }
                }
            }
        }
    }

    /**
     * 判定用户是否符合推广规则--进行奖励机制--为用户发放红包--在形成红包之后操作
     * 用户满足条件后就进行红包的发放
     * @param $uid
     */
    public static function send_red_packet($uid)
    {
        //判定当前用户是否是符合推广新手的规则
        $money = 3000;
        $days = 30;
        $count = self::get_continue_money($uid, $money, $days);
        if ($count['count'] >= $days) {
            //获取用户手机号
            $info = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
            $invite_phone = $info['phone'];
            //符合规则--可以发放红包了
            //产生的红包--一级推荐关系---》》活动红包列表
            $red_list = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_FALSE])->asArray()->all();
            if ($red_list !== null) {
                foreach ($red_list as $key => $value) {
                    $sender_uid = $value['sender_uid'];
                    $phone = $value['sender_phone'];
                    $actibity_source = $value['red_packet_name'];
                    $red_packet = $value['red_packet'];
                    //判定红包表数据库里面是否有数据
                    $flag = ActivityLog::find()->where(['uid' => $sender_uid, 'phone' => $phone, 'invite_id' => $uid, 'actibity_source' => $actibity_source, 'red_packet' => $red_packet])->one();
                    if ($flag == null) {
                        //增加红包记录
                        $log = new ActivityLog();
                        $log->uid = $sender_uid;
                        $log->phone = $phone;
                        $log->red_packet = $red_packet;
                        $log->actibity_source = $actibity_source;
                        $log->invite_id = $uid;
                        $log->invite_phone = $invite_phone;
                        $log->inviter_draw = ActivityLog::STATUS_INVITER_DRAW_SUCC;
                        $log->invitee_draw = ActivityLog::STATUS_INVITEE_DRAW_SUCC;
                        $log->status = ActivityLog::STATUS_SUCCESS;
                        $log->type = ActivityLog::RED_BOTHWAY_TO_INVITER;
                        $log->end_at = time() + 100 * 86400;
                        if ($log->save()) {
                            //更新推荐注册表里的数据
                            UserRecommend::updateAll(['is_grant' => UserRecommend::GRANT_TRUE], ['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_FALSE, 'red_packet' => $red_packet, 'sender_uid' => $sender_uid]);
                        }
                    }
                }
            }
        }
        //判定用户是否符合’推广大师‘的资格  60天连续投资6000元
        $money = 6000;
        $days = 60;
        $count = self::get_continue_money($uid, $money, $days);
        if ($count['count'] >= $days) {
            //获取用户手机号
            $info = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
            $invite_phone = $info['phone'];
            //符合规则--可以发放红包了
            //产生的红包--二级推荐关系---》》活动红包列表
            $red_list = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_FALSE])->asArray()->all();
            if ($red_list !== null) {
                foreach ($red_list as $key => $value) {
                    $sender_uid = $value['sender_uid'];
                    $phone = $value['sender_phone'];
                    $actibity_source = $value['red_packet_name'];
                    $red_packet = $value['red_packet'];
                    //判定红包表中是否已经有数据
                    $flag = ActivityLog::find()->where(['uid' => $sender_uid, 'phone' => $phone, 'invite_id' => $uid, 'actibity_source' => $actibity_source, 'red_packet' => $red_packet])->one();
                    if ($flag == null) {
                        //增加红包记录
                        $log = new ActivityLog();
                        $log->uid = $sender_uid;
                        $log->phone = $phone;
                        $log->red_packet = $red_packet;
                        $log->actibity_source = $actibity_source;
                        $log->invite_id = $uid;
                        $log->invite_phone = $invite_phone;
                        $log->inviter_draw = ActivityLog::STATUS_INVITER_DRAW_SUCC;
                        $log->invitee_draw = ActivityLog::STATUS_INVITEE_DRAW_SUCC;
                        $log->status = ActivityLog::STATUS_SUCCESS;
                        $log->type = ActivityLog::RED_BOTHWAY_TO_INVITER;
                        $log->end_at = time() + 100 * 86400;
                        if ($log->save()) {
                            //更新推荐注册表里的数据
                            UserRecommend::updateAll(['is_grant' => UserRecommend::GRANT_TRUE], ['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_FALSE, 'red_packet' => $red_packet, 'sender_uid' => $sender_uid]);
                        }
                    }
                }
            }
        }
    }

    /**
     * 用户注册记录用户推荐信息
     * 用户完成注册后--增加网站的推荐关系记录表
     * @param $uid
     */
    public static function produce_recommend($uid)
    {
        //获取注册用户信息
        $member = UcenterMember::find()->where(['id' => $uid])->asArray()->one();
        if ($member !== null) {
            $invitation_id = $member['invitation_id'];
            if ($invitation_id != 0) {
                $flag = UserRecommend::find()->where(['owner_uid' => $invitation_id, 'level' => 1, 'sender_uid' => $member['id'], 'is_grant' => UserRecommend::GRANT_RECOMMEND])->one();
                //数据库内部存在记录
                if ($flag == null) {
                    //进行一级推荐表的增加
                    $log = new UserRecommend();
                    $log->owner_uid = $invitation_id;
                    $log->level = 1;
                    $log->sender_uid = $member['id'];
                    $log->sender_phone = $member['phone'];
                    $log->sender_real_name = $member['real_name'];
                    $log->sender_register_time = $member['created_at'];
                    $log->sender_parent_id = $member['invitation_id'];
                    $log->demand_money = 0;
                    $log->demand_days = 0;
                    $log->red_packet = 30;
                    $log->red_packet_name = '推广新手';
                    $log->is_grant = UserRecommend::GRANT_RECOMMEND;
                    if ($log->save() == false) {
                        $return = array(
                            'errorNum' => '1',
                            'errorMsg' => '一级推荐关系记录失败',
                            'data' => null
                        );
                        return $return;
                    }
                }
                //获取父类用户的推荐关系
                $parent = UcenterMember::find()->where(['id' => $invitation_id, 'type' => UcenterMember::TYPE_UNLOCK])->asArray()->one();
                $parent_invitation_id = $parent['invitation_id'];
                if (!empty($parent_invitation_id) && $parent_invitation_id != $invitation_id) {
                    //存在二级推荐表的增加
                    $flag = UserRecommend::find()->where(['owner_uid' => $parent_invitation_id, 'level' => 2, 'sender_uid' => $member['id'], 'is_grant' => UserRecommend::GRANT_RECOMMEND])->one();
                    if ($flag == null) {
                        $log = new UserRecommend();
                        $log->owner_uid = $parent_invitation_id;
                        $log->level = 2;
                        $log->sender_uid = $member['id'];
                        $log->sender_phone = $member['phone'];
                        $log->sender_real_name = $member['real_name'];
                        $log->sender_register_time = $member['created_at'];
                        $log->sender_parent_id = $member['invitation_id'];
                        $log->demand_money = 0;
                        $log->demand_days = 0;
                        $log->red_packet = 20;
                        $log->red_packet_name = '推广大师';
                        $log->is_grant = UserRecommend::GRANT_RECOMMEND;
                        if ($log->save() == false) {
                            $return = array(
                                'errorNum' => '1',
                                'errorMsg' => '二级推荐关系记录失败',
                                'data' => null
                            );
                            return $return;
                        }
                    }
                }
                //数据存储没有错误--返回正确信息
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            } else {
                //不存在推荐关系---不进行数据记录
                $return = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => null
                );
                return $return;
            }
        } else {
            $return = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null
            );
            return $return;
        }
    }

    /**
     * 获取用户推荐关系列表
     * @param $uid
     * @param $page
     * @param $num
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function get_recommend_relation($uid, $page = 1, $num = 10)
    {
        //获取推荐列表
        $page = ($page - 1) * $num;
        //一级推荐人列表
        $leval_one_list = UserRecommend::find()->select([ 'sender_phone', 'is_grant', 'sender_register_time', 'update_at', 'red_packet', 'red_packet_name'])->where(['owner_uid' => $uid, 'level' => 1])->orderBy('is_grant desc')->limit($num)->offset($page)->asArray()->all();
        //一级推荐人的总人数
        $leval_one_count = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1])->count();
        //一级推荐人中，用户尚未领取的人数
        $leval_one_count_get_false = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_FALSE])->count();
        //一级推荐人中--用户尚未领取的红包总金额
        $leval_one_money_get_false = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_FALSE])->sum('red_packet');
        //推荐人--已经领取的人数
        $leval_one_count_get_true = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_TRUE])->count();
        //推荐人--已经领取的红包金额
        $leval_one_money_get_true = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 1, 'is_grant' => UserRecommend::GRANT_TRUE])->sum('red_packet');

        //二级推荐总的人数
        $leval_two_count = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2])->count();
        //二级推荐人中，用户尚未领取的人数
        $leval_two_count_get_false = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_FALSE])->count();
        //二级推荐人中，用户尚未领取的红包总额度
        $leval_two_money_get_false = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_FALSE])->sum('red_packet');
        //二级推荐人中--已经领取的人数
        $leval_two_count_get_true = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_TRUE])->count();
        //二级推荐人中 --已经放到红包表里面了的红包总金额
        $leval_two_money_get_true = UserRecommend::find()->where(['owner_uid' => $uid, 'level' => 2, 'is_grant' => UserRecommend::GRANT_TRUE])->sum('red_packet');

        ///获取用户手机号
        $user_phone = '186****1725';
        $user = UcenterMember::findOne(['id' => $uid]);
        if($user !== null){
            $user_phone = $user->phone;
            $user_code = \frontend\actions\App\AloneMethod::encrypt($user_phone);
        }

        $user_phone = substr_replace($user_phone,'****',3,4);
        $data = array(
             array(
                 'level' => 1,
                 'people_sum' => $leval_one_count,
                 'get_num' => $leval_one_count_get_true,
                 'get_money' => $leval_one_money_get_true ? $leval_one_money_get_true : 0,
                 'not_get_num' => $leval_one_count_get_false,
                 'not_get_money' => $leval_one_money_get_false ? $leval_one_money_get_false : 0,
                 'people_list' => $leval_one_list,
             ),
             array(
                 'level' => 2,
                 'people_sum' => $leval_two_count,
                 'get_num' => $leval_two_count_get_true,
                 'get_money' => $leval_two_money_get_true ? $leval_two_money_get_true : 0,
                 'not_get_num' => $leval_two_count_get_false,
                 'not_get_money' => $leval_two_money_get_false ? $leval_two_money_get_false : 0,
                 'people_list' => null
             ),
            'share' => array(
                'share_link' => 'https://www.licaiwang.com/events/festival20151015?code='.$user_code,
//                'share_title' => '理财王“推广大师”上线，月入20万红包，触手可得',
//                'share_info' => '为了和各位尊敬的投资者一起成长，理财王理财平台制订了推广大师计划，让您足不出户，利用业余时间轻松月入最多20万，快来加入我们吧。',
                'share_title' => '理财王壕送大礼啦！',
                'share_info' => '您的朋友'.$user_phone.'给您送来了8888元大红包，快来看看吧~~~',
                'share_logo' => 'http://static.licaiwang.com/imgs/logo180180.png'
            )
        );
        $return_array = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $data
        );
        return $return_array;
    }

    /**
     *通过标题获取单条文章内容
     * @param $title
     * @return array
     */
    public static function get_single_article($title){
        $article = Article::find()->where(['title' => $title,'status' => 1])->asArray()->one();
        if($article != null){
            $return_array = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $article
            );
            return $return_array;
        }else{
            $return_array = array(
                'errorNum' => '1',
                'errorMsg' => '暂无数据',
                'data' => null
            );
            return $return_array;
        }
    }

    /**
     * 根据分类查找文章列表
     * @param $title
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function get_category_article($title){
        $category = Category::find()->where(['title' => $title,'status' =>Category::STATUS_ACTIVE])->one();
        if($category !== null){
            $cid = $category->id;
            $articles = Article::find()->select(['title','intro','content','logo','create_at'])->where(['category_id' => $cid,'status' => 1])->asArray()->all();
            if($articles !== null){
                $data = array('list'=>$articles);
                $return_array = array(
                    'errorNum' => '0',
                    'errorMsg' => 'success',
                    'data' => $data
                );
                return $return_array;
            }else{
                $return_array = array(
                    'errorNum' => '1',
                    'errorMsg' => '暂无数据',
                    'data' => null
                );
                return $return_array;
            }
        }else{
            $return_array = array(
                'errorNum' => '1',
                'errorMsg' => '分类信息不存在',
                'data' => null
            );
            return $return_array;
        }
    }

    /**
     * 获取用户信息
     */
    public static function  statistics(){
        $users = UcenterMember::find()->asArray()->all();
        $data = array();
        if($users !== null){
            foreach($users as $key => $value){
                $data[$key]['id'] = $value['id'];
                $data[$key]['phone'] = $value['phone'];
                $data[$key]['real_name'] = $value['real_name'];
                $data[$key]['created_at'] = date('Y-m-d :H-m-s',$value['created_at']);
                $data[$key]['create_area'] = $value['create_area'];
                $status = $value['status'];
                if($status == UcenterMember::STATUS_ACTIVE){
                    $data[$key]['status'] = '注册用户';
                }elseif($status == UcenterMember::STATUS_REAL){
                    $data[$key]['status'] = '实名用户';
                }elseif($status == UcenterMember::STATUS_BIND){
                    $data[$key]['status'] = '绑卡用户';
                }
                //账户信息
                $info = Info::find()->where(['member_id' => $value['id']])->one();
                //账户余额
                $data[$key]['balance'] = $info['balance'];
                //在投资金
                $data[$key]['invest'] = $info['invest'];
                //可用收益
                $data[$key]['profit'] = $info['profit'];
                //累计收益
                $data[$key]['total_revenue'] = $info['total_revenue'];
                //充值--投资--赎回--提现
                //首次充值
                $rechar_at = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_RECHAR_SUC])->orderBy('id asc')->one();
                //充值总金额
                $rechar_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_RECHAR_SUC])->sum('step');
                //首次投资时间
                $invest_at = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_INVEST_SUC])->orderBy('id asc')->one();
                //投资总额
                $invest_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_INVEST_SUC])->sum('step');
                //赎回总额
                $redem_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_REDEM_SUC])->sum('step');
                //提现总额
                $withdraw_sum = Log::find()->where(['member_id'=> $value['id'],'status' => Log::STATUS_WITHDRAW_SUC])->sum('step');

                //首次充值时间
                $data[$key]['rechar_at'] =  $rechar_at ? date('Y-m-d :H-m-s',$rechar_at['create_at']) : 0;
                $data[$key]['rechar_sum'] = $rechar_sum;
                //首次投资时间
                $data[$key]['invest_at'] = $invest_at ?  date('Y-m-d :H-m-s',$invest_at['create_at']) : 0;
                $data[$key]['invest_sum'] = $invest_sum;
                //赎回
                $data[$key]['redem_sum'] = $redem_sum;
                //提现
                $data[$key]['withdraw_sum'] = $withdraw_sum;
                //未领取红包奖励
                $red = member::get_user_red_packet($value['id']);
                $data[$key]['red_usable'] = $red['data']['red_sum'];
                //总的红包奖励
                $red1 = member::get_rad_list($value['id']);
                $data[$key]['red_sum'] = $red1['data']['sum'];

            }
           return  Utils::exportExcel($data,array('用户id','用户手机号','用户真实姓名','用户注册时间','用户注册地区','用户状态','用户账户余额','在投资金','可用收益','累计收益','首次充值时间','充值总金额','首次投资时间','投资总额','赎回总额','提现总额','未领取红包','红包奖励总额'), '数据分析'.date('Y-m-d-H-m-s'));
        }
    }

}

