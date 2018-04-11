<?php

namespace backend\controllers;

use common\models\base\activity\UserRecommend;
use common\models\base\fund\Order;
use common\models\base\fund\Income;
use common\models\base\fund\Product;
use common\models\base\fund\Common;
use common\models\base\fund\Daycommon;
use common\models\base\activity\RaiseCard;
use common\models\fund\FundProductThirdproduct;
use common\models\base\asset\Info;
use framework\helpers\Utils;
use framework\base\ErrorException;
use common\models\UcenterMember;
use frontend\actions\AloneMethod;
use frontend\actions\Port;
use common\models\setting\Setting;
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\post\SignIn;
class IncomeController extends \yii\web\Controller
{

    public function actionIndex()
    {
        error_reporting(0);

        //取出后台设置最大红包
        $maxincome = Setting::getCode('maxincome');
        //取出后台设置的每人最大红白
        $maxpeople = Setting::getCode('maxpeople');
        //后台设置体验金利率
        $goldrate = Setting::getCode('goldrate');
        //设定时区
        date_default_timezone_set('PRC');
        //取出当前时间
        $btime = date('Y-m-d'.'00:00:00',time());
        //转换成“开始”的时间戳
        $btimestr = strtotime($btime);
        //

        //判定今日是否已经计算了收益---防止收益被多次计算
        $count = Income::find()->where(['>','created_at',$btimestr])->count();
        if($count){
            return '收益已经计算过了';
        }

        //查询昨天以前所有的投资订单，查询order表
        $order = Order::find()->select('member_id,sum(money) as smoney,product_id')
                              ->andWhere(['<', 'start_at', Utils::alldaytostr($btimestr)[1]])
                              ->andWhere(['status'=>1])
                              ->groupBy(['member_id','product_id'])
                              ->asArray()
                              ->all();
        $arrnew = array();

        //循环所有投资
//        $test = 0;
        foreach($order as $k=>$v){
            //查询订单下对应项目
            $product = Product::findOne($v['product_id']);
            //查询昨天的生成的利息
            $income  = Income::find()
                ->andWhere(['member_id'=>$v['member_id']])
                ->andWhere(['between', 'created_at', Utils::alldaytostr($btimestr)[0], Utils::alldaytostr($btimestr)[1]])
                ->one();

            //查询增值卡昨天信息
//            $railscard = RaiseCard::find()
//                ->select('member_id,sum(rate) as srate')
//                ->andWhere(['member_id'=>$v['member_id']])
//                ->andWhere(['<', 'use_at',Utils::alldaytostr($btimestr)[1]])
//                ->andWhere(['>', 'use_end_time',Utils::alldaytostr($btimestr)[1]])
//                ->andWhere(['status'=>RaiseCard::STATUS_USE])
//                ->groupBy(['member_id'])
//                ->asArray()
//                ->one();
            //昨日签到人数
            $count = SignIn::find()->joinWith(['info' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */
                $query->andWhere(['>','asset_info.invest','1000']);
            }])
            ->andWhere(['between', 'sign_in.sign_in_time',Utils::alldaytostr($btimestr)[0],Utils::alldaytostr($btimestr)[1]])
            ->asArray()
            ->count();


            //每人赠送利息，如果每人分的利息大于设置的每人获得的最大利息，就用设置的每人利息，如果小于就用分的利息
            $everyincome = 0;
            if($count){
                $everyincome = $maxincome/$count > $maxpeople? $maxpeople :$maxincome/$count;
            }


            //取出昨天这个人有没有签到
            $rails = SignIn::find()
                ->andWhere(['uid'=>$v['member_id']])
                ->andWhere(['between', 'sign_in_time',Utils::alldaytostr($btimestr)[0],Utils::alldaytostr($btimestr)[1]])
                ->asArray()
                ->one();



            //判断订单是否有总金额
            $smoney = isset($v['smoney'])? $v['smoney'] :0;

            //查询该用户体验金
//            $all = Gold::find()->select('experience_gold.id,experience_gold.rid,sum(experience_gold.money) as gmoney')->andWhere(['uid'=>$v['member_id']])->joinWith(['rule' => function ($query) {
//                /** @var \yii\db\ActiveQuery $query */
//                $query->andWhere(['experience_rule.status'=>Rule::STATUS_ACTIVE]);
//            }])->asArray()->all();
//
//            //查询发放给该用户体验金昨天信息
//            $sendgold = RaiseCard::find()
//                ->select('member_id,sum(rate) as srate')
//                ->andWhere(['member_id'=>$v['member_id']])
//                ->andWhere(['<', 'validity_start_at',Utils::alldaytostr($btimestr)[1]])
//                ->andWhere(['>', 'validity_out_at',Utils::alldaytostr($btimestr)[1]])
//                ->andWhere(['status'=>RaiseCard::STATUS_USE])
//                ->asArray()
//                ->one();

            //查询该用户体验金-----TODO
            $all = Gold::find()
                        ->select('uid,sum(money) as smoney')
                        ->andWhere(['uid'=>$v['member_id'],'status' => Gold::STATUS_ACTIVE])
                        ->andWhere(['<', 'created_at',time()])
                        ->andWhere(['>', 'end_at',time()])
                        ->asArray()
                        ->one();

//            $summoney = 0;
//            foreach($all as $kmmy=>$vmmy){
//                $newbetime = strtotime(date('Y-m-d'.' 00:00:00',time()));
//                $endtime = $vmmy['created_at'] + 3600 * 24 * $vmmy['time'];
//                if($vmmy['created_at']<$newbetime && $endtime>$newbetime){
//                    $summoney +=$all[$kmmy]['money'];
//                }
//            }
//            var_dump($summoney);
//            var_dump($all);
           //查询发放给该用户体验金昨天信息
//            $sendgold = RaiseCard::find()
//                ->select('member_id,sum(rate) as srate')
//                ->andWhere(['member_id'=>$v['member_id']])
//                ->andWhere(['<', 'validity_start_at',Utils::alldaytostr($btimestr)[1]])
//                ->andWhere(['>', 'validity_out_at',Utils::alldaytostr($btimestr)[1]])
//                ->groupBy(['member_id'])
//                ->asArray()
//                ->one();

            //计算利率，增息卡的利率+项目的利率
//            $railscardrate = isset($railscard)? $railscard['srate']+$product->rate/365:$product->rate/365;
            //TODO
            $railscardrate = isset($railscard)? $railscard['srate'] + $product->rate : $product->rate;

            //活动期间利率调整TODO
            date_default_timezone_set('PRC');
            //活动开始时间
            $begin_time = strtotime('2015-10-1');
            //活动结束时间
            $end_time = strtotime('2015-10-7');
            //当前时间
            $now_time = time();
            if($now_time > $begin_time && $now_time < $end_time){
                $railscardrate = '0.101';
            }
            $railscardrate = '0.08';


            //组成新数组，用户的id
            $arrnew[$v['member_id']]['member_id'] = $v['member_id'];

            //用户昨日利息，因利息也会产生利息，所以昨日利息乘以利率
            // $arrnew[$v['member_id']]['smoney'] += isset($income)?($smoney+$income->smoney)*Utils::moneyFormat($railscardrate) :$smoney*Utils::moneyFormat($railscardrate);
            //投资总额---记录当前再投资金
             $arrnew[$v['member_id']]['money'] += $v['smoney'];

            //计算收益--TODO
            $arrnew[$v['member_id']]['smoney'] += ceil(($smoney * $railscardrate*100)/365)/100;
            //取不包含红包体验金收益
            $arrnew[$v['member_id']]['iincome'] += ceil(($smoney * $railscardrate*100)/365)/100;
            //用户的昨日之前的累计利息
           // isset($income)? $arrnew[$v['member_id']]['newmoney'] = $arrnew[$v['member_id']]['smoney']+$income->newmoney : $arrnew[$v['member_id']]['newmoney'] += $smoney*Utils::moneyFormat($railscardrate);

            // TODO
            isset($income)? $arrnew[$v['member_id']]['newmoney'] = ($arrnew[$v['member_id']]['smoney']+$income->newmoney) : ($arrnew[$v['member_id']]['newmoney'] += ceil(($smoney * $railscardrate*100)/365)/100);


            //签到获取的利息
            $arrnew[$v['member_id']]['railscard'] = $rails? $everyincome : 0 ;
            //在投金额大于1元--计算体验金收益
            if($smoney >= 1){
                //计算该用户体验金获取的利息
                $arrnew[$v['member_id']]['goldincome'] = ceil(($all['smoney'])*$railscardrate*100/365)/100;
            }


            //每人的利率
            $arrnew[$v['member_id']]['rate']  = $railscardrate;
        }

        foreach($arrnew as $k=>&$v){
            //求出每个用户的昨日利率
            $v['rate'] = Utils::moneyFormat(($v['smoney']+ $v['railscard'])*365/$v['money']);
            //总利息加上签到获取的利息
            $v['newmoney'] = $v['railscard'] + $v['newmoney'] + $v['goldincome'];
            //利息加上签到获取的利息
            $v['smoney'] = ($v['railscard'] + $v['smoney'] + $v['goldincome']);
            //利息计算时的投资---
//            $v['money'] = $v['money']  + $v['goldincome'] + $v['railscard'];
            // -- 计算在投金额不应该增加利息产生的金额
            $v['money'] = $v['money'];
        }


        //循环数组
        foreach($arrnew as $vs)
        {
            $_model = new Income();
            //查出用户表的余额
            $Info = Info::find()->where(['member_id'=> $vs['member_id']])->one();
            if($Info){
                $Info->profit = $Info->profit+$vs['smoney'];
                $Info->total_revenue = $Info->total_revenue+$vs['smoney'];
                $Info->save();
            }

            //取出昨天这个人有没有签到
            $signin = SignIn::find()
                ->andWhere(['uid'=>$vs['member_id']])
                ->andWhere(['between', 'sign_in_time',Utils::alldaytostr($btimestr)[0],Utils::alldaytostr($btimestr)[1]])
                ->one();
            if($signin && $vs['money']>= 1000){
                $signin->sign_in_money = $vs['railscard'];
                $signin->status = SignIn::STATUS_FINISH;
                $signin->save();
                $_model->railscard =$vs['railscard'];
            }
            $_model->member_id = $vs['member_id'];
            $_model->smoney = $vs['smoney'];
            $_model->money =$vs['money'];
            $_model->rate =$vs['rate'];
            $_model->newmoney =$vs['newmoney'];
            $_model->goldincome =$vs['goldincome'];

            $_model->iincome =$vs['iincome'];
            //生成每日利息表的数据
            $_model->save();
        }
        //推荐大师活动
        //获取所有用户信息
        $users = UcenterMember::find()->select(['id'])->asArray()->all();
        if($users !== null){
            //获取所有用户id值
            foreach($users as $key => $value){
                //循环用户信息--判定其推荐人中是否有符合奖励规则的人--有进行推荐表中状态更改
                AloneMethod::produce_red_packet($value['id']);
            }
        }
        //判定推荐表中用户是否符合拿取奖励规则
        if($users !== null){
            foreach($users as $key => $value){
                AloneMethod::send_red_packet($value['id']);
            }
        }



        echo "成功";
    }
    //存每天产生投资总额，计划任务，把common表的数据复制一份存储到每天common的表里
    public function actionDaycommon(){
        $common = new Common();
        $daycommon = new Daycommon();
        $day = $common->find()->one();
        $daycommon->invest_sum = $day->invest_sum;
        $daycommon->invest_people = $day->invest_people;
        $daycommon->invest_times = $day->invest_times;
        $daycommon->create_at = $day->create_at;
        $daycommon->update_at = $day->update_at;
        $daycommon->save($day);
    }
    public function actionSend(){
        try {
            Utils::sendEmail('wanhuceshi@163.com', '统计发送', '123');
        } catch (\Exception $e) {
            throw new ErrorException($e);
        }
    }

}
