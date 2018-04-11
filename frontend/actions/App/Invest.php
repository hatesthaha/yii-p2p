<?php
/**
 * Created by PhpStorm.
 * Auther: langxi
 * Date: 2015/7/11
 * Time: 5:00
 * 用户投资
 */
namespace frontend\actions\App;

use common\models\base\activity\HoldActivity;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\asset\LogQuery;
use common\models\base\asset\LogSearch;
use common\models\base\fund\Common;
use common\models\base\fund\product;
use common\models\base\fund\order;
use common\models\base\fund\Thirdorder;
use common\models\base\fund\Thirdproduct;
use common\models\base\fund\ProductThirdproduct;
use yii\base\ErrorException;
use common\models\UcenterMember;
use frontend\actions\Action;
use frontend\actions\sinapay;
use common\models\base\experience\Rule;
use common\models\base\experience\Gold;
use common\models\base\ucenter\Catmiddle;
use common\models\base\asset\ClerkLog;
use common\models\sinapay\SinaConfig;
use common\models\invation\AssetConfig;

class Invest extends Action
{
    const  INVESTSUCCEED = 2; //投资成功状态码
    const  INVESTERROR = -2; //投资失败状态码

    /**
     * Auther:langxi
     *
     * 查看网站总的投资数据
     */
    public static function see_total()
    {
        $common = Common::find()->where(['id' => '1'])->asArray()->one();
        if (!$common) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => $common,
        );
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 获取项目列表
     */
    public static function product_list($page_num, $page)
    {
        //显示的页数
        $show_num = 10;
        //向上取整
        $page_limit = ceil($show_num/$page_num);

        $p = $page_num;//每页几条
        $num = $p * ($page - 1);//页数
        //按id排序['and', 'id=1', 'id=2']
        $product = Product::find()->andwhere(['<>', 'status', Product::STATUS_LOCK])->andWhere(['>', 'create_at', 1441641600])->orderBy('id desc')->limit($p)->offset($num)->asArray()->all();
        $count = Product::find()->andwhere(['<>', 'status', Product::STATUS_LOCK])->andWhere(['>', 'create_at', 1441641600])->count();
        if (!$product && !$count) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '暂无项目',
                'data' => null,
            );
            return $result;
        }elseif(!$product && $count || $page >$page_limit){
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '没有更多项目了',
                'data' => null,
            );
            return $result;
        } else {
            $lists = array();
            foreach ($product as $key => $value) {
                //TODO--修改虚拟人数功能
                $lists[$key]['id'] = $value['id'];
                $lists[$key]['title'] = $value['title'];
                $lists[$key]['intro'] = $value['intro'];
                //项目总金额
                $lists[$key]['amount'] = $value['amount'] + $value['virtual_amonnt'];
                $lists[$key]['rate'] = $value['rate'];
                //项目投资人数
                $lists[$key]['invest_people'] = $value['invest_people'] ? $value['invest_people'] + $value['virtual_invest_people'] : $value['invest_people'];
                //项目的投资金额
                $lists[$key]['invest_sum'] = $value['invest_people'] ? $value['invest_sum'] + $value['virtual_amonnt'] : $value['invest_sum'];

                $lists[$key]['each_max'] = $value['each_max'];
                $lists[$key]['each_min'] = $value['each_min'];
                $lists[$key]['start_at'] = $value['start_at'];
                $lists[$key]['end_at'] = $value['end_at'];
                if ($value['start_at'] > time()) {
                    $lists[$key]['status'] = '0';//待售
                } else {
                    if ($value['end_at'] < time()) {
                        //项目已经结束售卖
                        $lists[$key]['status'] = '2';//售罄
                    } else {
                        if ($value['amount'] > $value['invest_sum']) {
                            $lists[$key]['status'] = '1';//在售
                        } else {
                            $lists[$key]['status'] = '2';//售罄
                        }
                    }
                }
            }

            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $lists,
            );
            return $result;
        }

    }


    /**
     * Auther:langxi
     *
     * App接口返回项目详细信息
     */
    public static function xproduct($product_id)
    {
        $product_id = (int)$product_id;
        if (!$product_id || !is_numeric($product_id) || !is_int($product_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        //$product = Product::find()->where(['id'=>$product_id])->asArray()->one();
        $product = (new \yii\db\Query())
//            ->select(['title', 'intro', 'amount', 'start_at', 'end_at', 'rate', 'invest_people', 'invest_sum', 'each_max', 'each_min'])
            ->from('fund_product')
            ->where(['id' => $product_id])
            ->one();
        if (!$product) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        } else {
            //增加虚拟人数功能
            $data = array(
                'title' => $product['title'],
                'intro' => $product['intro'],
                //项目总额
                'amount' => $product['amount'] + $product['virtual_amonnt'],
                'start_at' => $product['start_at'],
                'end_at' => $product['end_at'],
                'rate' => $product['rate'],
                //项目投资人数
                'invest_people' =>$product['invest_people'] ? $product['invest_people'] + $product['virtual_invest_people'] : $product['invest_people'],
                //项目投资总数
                'invest_sum' => $product['invest_people'] ? $product['invest_sum'] + $product['virtual_amonnt'] : $product['invest_sum'],

                'each_max' => $product['each_max'],
                'each_min' => $product['each_min']
            );
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
     * 返回项目的可投金额
     */
    public static function kmoney($product_id)
    {
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if (!$product) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $product_money = $product['amount'] - $product['invest_sum'];
        $result = array(
            'errorNum' => '0',
            'errorMsg' => 'success',
            'data' => array('money' => $product_money),
        );
        return $result;
    }

    /**
     * Auther:langxi
     *
     * 返回用户的投资记录
     */
    public static function InvestLog($member_id)
    {
        $member_id = (int)$member_id;
        if (!$member_id || !is_numeric($member_id) || !is_int($member_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $is_user = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
        if (!$is_user) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该用户不存在',
                'data' => null,
            );
            return $result;
        }

        $log = (new \yii\db\Query())
            ->select(['step', 'product_id', 'status'])
            ->from('asset_log')
            ->where(['member_id' => $member_id])
            ->all();
        if (!$log) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该用户暂无记录',
                'data' => null,
            );
            return $result;
        } else {
            $result = array(
                'errorNum' => '0',
                'errorMsg' => 'success',
                'data' => $log,
            );
            return $result;
        }
    }

    /**
     * Auther:langxi
     *
     *体验金
     */
    public static function gold($member_id, $money)
    {
//        $order = Order::find()->select(['id'])->where(['member_id' => $member_id])->andWhere(['>=', 'start_money', '1'])->asArray()->all();
//        $is_order = count($order);

        $flag = Gold::find()->where(['uid' => $member_id,'rid' => '3'])->one();
        if(!$flag){
            $info = Info::find()->where(['member_id' => $member_id])->one();
            $invest = $info->invest;
            if($invest >= 1){
                $rule = Rule::find()->where(['id' => '3', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
                //判断规则是否生效
                if ($rule['time']) {
                    $rul_money = $rule['money'];
                    $end_at = time() + $rule['time'] * 24 * 3600;

                    $gold = new Gold();
                    $gold->uid = $member_id;
                    $gold->rid = '3';
                    $gold->money = $rul_money;
                    $gold->end_at = $end_at;
                    $gold->title = '中秋新用户体验金';
                    $gold = $gold->save();

                }

                //判断是否有邀请人
                $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
                if ($member['invitation_id']) {
                    $rule = Rule::find()->where(['id' => '5', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
                    //判断规则是否生效
                    if($rule['time']){
                        $rul_money = $rule['money'];
                        $end_at = time() + $rule['time'] * 24 * 3600;

                        $gold = new Gold();
                        $gold->uid = $member['parent_member_id'];
                        $gold->rid = '5';
                        $gold->money = $rul_money;
                        $gold->end_at = $end_at;
                        $gold = $gold->save();
                    }

                }
            }

        }

//
//        if ($money >= 1 && $is_order == 1) {
//            $rule = Rule::find()->where(['id' => '3', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
//            //判断规则是否生效
//            if ($rule['time']) {
//                $rul_money = $rule['money'];
//                $end_at = time() + $rule['time'] * 24 * 3600;
//
//                $gold = new Gold();
//                $gold->uid = $member_id;
//                $gold->rid = '3';
//                $gold->money = $rul_money;
//                $gold->end_at = $end_at;
//                $gold = $gold->save();
//
//            }
//
//            //判断是否有邀请人
//            $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
//            if ($member['invitation_id']) {
//                $rule = Rule::find()->where(['id' => '5', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
//                //判断规则是否生效
//                if($rule['time']){
//                    $rul_money = $rule['money'];
//                    $end_at = time() + $rule['time'] * 24 * 3600;
//
//                    $gold = new Gold();
//                    $gold->uid = $member['parent_member_id'];
//                    $gold->rid = '5';
//                    $gold->money = $rul_money;
//                    $gold->end_at = $end_at;
//                    $gold = $gold->save();
//                }
//
//            }
//        }
    }


    public static function goldtwo($member_id, $money)
    {
        //获取举办活动
        $holdactivity = HoldActivity::find()->where(['id' => 7])->asArray()->one();
        //获取使用的规则
        $ridearray = array();
        $ridearray = explode(',',$holdactivity['rid_list']);
        //获取应该得到的体验金数量
        $experience_gold = 0;
        $experience_gold = $holdactivity['gold_money'];
        //体验金的有效时间
        $gold_day = 0;
        $gold_day = $holdactivity['gold_day'];
        //活动开始时间
        $activity_begin = time();
        $activity_begin = $holdactivity['activity_begin'];
        //活动结束时间
        $activity_end = time();
        $activity_end = $holdactivity['activity_end'];
        //首次投资送体验金规则

      if(in_array('3',$ridearray))
      {
          //获取规则情况
        $flag =  Rule::find()->where(['id' => '3', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
          //存在规则
        if($flag){
            $info = Info::find()->where(['member_id' => $member_id])->one();
            //获取再投资金
            $invest = $info->invest;
            //用户注册时间
            $create_at = $info->create_at;
            //再投资金大于1元
            if($invest >= 1 && $create_at > $activity_begin && $create_at < $activity_end ){
                //判断规则是否生效
                $now = time();
                if ($now > $activity_begin && $now < $activity_end) {

                    //判定用户是否领取了体验金
                    $is_get  = Gold::find()->where(['rid' => 3,'uid' => $member_id,'title' => '国庆佳节新用户投资送体验金'])->asArray()->one();
                    if(!$is_get){
                        //计算体验金截止时间
                        $end_at = time() + $gold_day * 24 * 3600;
                        //给用户发体验金
                        $gold = new Gold();
                        $gold->uid = $member_id;
                        $gold->rid = '3';
                        $gold->money = $experience_gold;
                        $gold->end_at = $end_at;
                        $gold->status = Gold::STATUS_ACTIVE;
                        $gold->title = '国庆佳节新用户投资送体验金';
                        $gold->save();
                    }
                }
                //判断是否有邀请人
                $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
                if ($member['invitation_id']) {
                    if(in_array('5',$ridearray)){
                        $rule = Rule::find()->where(['id' => '5', 'status' => Rule::STATUS_ACTIVE])->asArray()->one();
                        //规则启用
                        if($rule){
                            //判断规则是否在时间内
                            $now = time();
                            if ($now > $activity_begin && $now < $activity_end) {
                                //判定用户是否领取了体验金
                                $is_get  = Gold::find()->where(['rid' => 3,'uid' => $member_id,'title' => '国庆佳节推荐用户投资送体验金'])->asArray()->one();
                                if(!$is_get){
                                    //计算体验金截止时间
                                    $end_at = time() + $gold_day * 24 * 3600;
                                    //给用户发体验金
                                    $gold = new Gold();
                                    $gold->uid = $member_id;
                                    $gold->rid = '3';
                                    $gold->money = $experience_gold;
                                    $gold->end_at = $end_at;
                                    $gold->status = Gold::STATUS_ACTIVE;
                                    $gold->title = '国庆佳节推荐用户投资送体验金';
                                    $gold->save();
                                }
                            }
                        }
                    }
                }
            }
        }
      }
    }

    /**
     * Auther:langxi
     * $member_id:用户id,$product_id:项目id,$money:用户投资钱数
     * 用户投资
     */
    public static function invest($member_id, $product_id, $money)
    {
        ini_set('max_execution_time', 60);
        $member_id = (int)$member_id;
        $product_id = (int)$product_id;
        $money = (double)$money;

        //判断用户是否可进行投资操作
        $is_go = Info::find()->select(['status'])->where(['member_id' => $member_id])->asArray()->one();
        if ($is_go['status'] > 0) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '处理中，请稍后再试',
                'data' => null,
            );
            return $result;
        }
        Info::updateAll(['status' => Info::GO_TWO], ['member_id' => $member_id]);//进行操作，状态变为投资处理中

        //检测用户投资资金，次数
        $check_invest = self::check_invest($member_id, $product_id, $money);
        if ($check_invest['errorNum']) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return $check_invest;
        }


        //检测用户状态
        $checkMember = self::checkMember($member_id);
        if ($checkMember) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return $checkMember;
        }
        //检测用户账户余额是否满足投资金额
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        if ($card['balance'] < $money) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '账户金额不足，请进行充值',
                'data' => null,
            );
            return $result;
        }

        //检测投资金额是否满足项目的每次投资最大最小额度限制
        $checkMoney = self::checkMoney($product_id, $money);
        if ($checkMoney) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return $checkMoney;
        }
        //检测项目状态
        $checkProduct = self::checkProduct($product_id, $money);
        if ($checkProduct) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return $checkProduct;
        }

        $asset = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $bank_card = $asset['bank_card'];//用户银行卡
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();

        $end_at = $product['end_at']; //项目投资终止时间


        //事物回滚
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //将账户中的钱取出，放入冻结金额中
            $asset = Info::findOne($member_id);
            $asset->balance = $asset['balance'] - $money;
            $asset->freeze = $asset['freeze'] + $money;
            $asset = $asset->save();
            if (!$asset) {
                throw new ErrorException('投资失败', 4002);
            }

            if ($product['type'] == Product::TYPE_THIRD) {
                //进行投资将钱放入order中  type=1债权转让项目（一对多）
                $order = new Order();
                $order->member_id = $member_id;
                $order->product_id = $product_id;
                $order->type = Product::TYPE_THIRD;
                $order->money = $money;
                $order->start_money = $money;
                $order->status = Order::STATUS_ACTIVE;//1标识订单状态进行
                $order->start_at = time();
                $order->end_at = $end_at;
                $order = $order->save();

                if (!$order) {
                    throw new ErrorException('投资失败', 4002);
                }
            } else {
                //进行投资将钱放入order中   type=0债权项目（一对一）
                $order = new Order();
                $order->member_id = $member_id;
                $order->product_id = $product_id;
                $order->type = Product::TYPE_PRO;
                $order->money = $money;
                $order->start_money = $money;
                $order->status = Order::STATUS_ACTIVE;//1标识订单状态进行
                $order->start_at = time();
                $order->end_at = $end_at;
                $order = $order->save();

                if (!$order) {
                    throw new ErrorException('投资失败', 4002);
                }
            }
            //检测用户是否投资过该项目，进行项目投资人数加一处理，项目已投金额增长
            $check_people = self::check_people($member_id, $product_id);
            if ($check_people) {
                $product = Product::find()->where(['id' => $product_id])->asArray()->one();
                $product_money = $product['invest_sum'] + $money;
                $total_money = $product['amount'];
                if ($product_money > $total_money) {
                    throw new ErrorException('您投资的金额大于该项目剩余的额度', 4002);
                }
                $product = Product::findOne($product_id);
                $product->invest_sum = $product['invest_sum'] + $money;
                if ($product['amount'] - $product['invest_sum'] < 10) {
                    $product->status = Product::STATUS_OUT;
                }
                $product->invest_people = $product['invest_people'] + 1;
                $res = $product->save();

                if (!$res) {
                    throw new ErrorException('项目已投金额、投资人数增加失败', 4002);
                }
            } else {
                $product = Product::find()->where(['id' => $product_id])->asArray()->one();
                $product_money = $product['invest_sum'] + $money;
                $total_money = $product['amount'];
                if ($product_money > $total_money) {
                    throw new ErrorException('您投资的金额大于该项目剩余的额度', 4002);
                }
                $product = Product::findOne($product_id);
                $product->invest_sum = $product['invest_sum'] + $money;
                if ($product['amount'] - $product['invest_sum'] < 10) {
                    $product->status = Product::STATUS_OUT;
                }
                $res = $product->save();
                if (!$res) {
                    throw new ErrorException('项目已投金额增加失败', 4002);
                }
            }
            //type=1为一对多项目债权转让，等于0为债权项目一对一（只存在于product表中于thirdproduct无关）

            //获取用户此次投资此项目的债权字典
            $thirdArr = self::creditor_dic($product_id, $money);

            //按照生成的债权字典，将钱分配给债权表
            $setthird = self::set_Third($thirdArr, $member_id);

            if ($setthird) {
                return $setthird;
            }


            //删减用户账户冻结金额
            $asset = Info::find()->where(['member_id' => $member_id])->asArray()->one();
            $freeze = $asset['freeze'];//获取用户账户新的冻结金额
            $asset = Info::findOne($member_id);
            $asset->freeze = $freeze - $money;
            $asset->invest = $asset['invest'] + $money;
            $asset->total_invest = $asset['total_invest'] + $money;//网站投资流程完成，增加用户累计投资额
            $asset = $asset->save();
            if (!$asset) {
                throw new ErrorException('删减冻结金额失败', 4002);
            }

            //投资成功进行投资记录
            $assetlog = new Log();
            $assetlog->member_id = $member_id;
            $assetlog->product_id = $product_id;
            $assetlog->step = $money;
            $assetlog->action = 'Invest/invest';
            $assetlog->status = self::INVESTSUCCEED;//2标识投资成功
            $assetlog->bankcard = $bank_card;
            $assetlog->remark = '投资成功';
            $assetlog->save();

            //处理债权字典，生成用于第三方代收的数据字典，键值为用户id，值为钱数 , $sina_pay为第三方代收函数需要的参数
            $sina_dic = array();
            foreach ($thirdArr as $v) {
                $sina_dic['m' . $v['maxcreditor']][] = array($v['mcmoney'], $v['maxcreditor']);
                $sina_dic['c' . $v['creditor']][] = array($v['ocmoney'], $v['creditor']);
            }
            $sina_pay = array();
            foreach ($sina_dic as $key => $val) {
                foreach ($val as $k => $v) {
                    if (!isset($sina_pay[$v['1']])) {
                        $sina_pay[$v['1']] = $v['0'];
                    } else {
                        $sina_pay[$v['1']] += $v['0'];
                    }
                }
            }
            //过滤掉金额为空的数组,得到第三方代收函数需要的参数，$sina_pay
            foreach ($sina_pay as $key => $value) {
                if (empty($value)) unset($sina_pay[$key]);
            }

            //调用封装好的第三方接口
            $sina_invest = sinapay::invest((string)$member_id, (string)$product_id, (string)$money);//托管代收接口

            if ($sina_invest['errorNum']) {
                throw new ErrorException($sina_invest['errorMsg'], 7001);
            }

            $sina_peyee = sinapay::batchPay($sina_pay, $sina_invest['data']['out_trade_no']);//托管批量代付接口

            if ($sina_peyee['errorNum']) {
                $sina_refund = sinapay::hostingRefund($sina_invest['data']['identity_id'], $sina_invest['data']['out_trade_no'], $sina_invest['data']['money']);
                if ($sina_refund['errorNum']) {
                    throw new ErrorException($sina_refund['errorMsg'], 7001);
                }
                throw new ErrorException($sina_peyee['errorMsg'], 7001);
            }

            //总投资记录
            $total_log = self::total_log($member_id, $money);
            if ($total_log) {
                return $total_log;
            }

            $transaction->commit();
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            $result = array('errorNum' => '0', 'errorMsg' => 'success', 'data' => null);
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            //对投资失败信息进行记录
            $remark = $e->getMessage();
            $assetlog = new Log();
            $assetlog->member_id = $member_id;
            $assetlog->product_id = $product_id;
            $assetlog->step = $money;
            $assetlog->action = 'Invest/Invest';
            $assetlog->status = self::INVESTERROR;//-2标识投资失败
            $assetlog->bankcard = $bank_card;
            $assetlog->remark = $remark;
            $assetlog->save();
            $result = array('errorNum' => '1', 'errorMsg' => $remark, 'data' => null);
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return $result;

        }

    }


    /**
     * Auther:langxi
     *
     * 生成债权字典：生成用户投资金额随机分配给几个债权项目（现为3-5个）,并随机出钱数，按照钱数筛选债权项目，进行分配
     */
    private static function creditor_dic($product_id, $money)
    {
        $num = ceil(mt_rand(30000, 60000) / 10000);//生成随机数

        //取投资项目对应的债权项目中的可投资额度
        $productthirdproduct = ProductThirdproduct::find()->where(['product_id' => $product_id])->asArray()->all();
        $thirdArr = array();
        foreach ($productthirdproduct as $key => $val) {
            //获取债权项目的id,投资总额，已投资总额
            $thirdproduct = Thirdproduct::find()->where(['id' => $val['thirdproduct_id']])->asArray()->one();
            $thirdArr[$key]['thirdorder_id'] = $thirdproduct['id']; //债权订单id
            $thirdArr[$key]['k_money'] = $thirdproduct['ocmoney'] + $thirdproduct['mcmoney']; //债权表剩余的可投金额, $thirdproduct['ocmoney']为原始债权金额,$thirdproduct['mcmoney']为最大债权金额
            $thirdArr[$key]['end_at'] = $thirdproduct['end_at']; //债权标结束时间
            $thirdArr[$key]['ocmoney'] = $thirdproduct['ocmoney'];//原始债权金额
            $thirdArr[$key]['mcmoney'] = $thirdproduct['mcmoney'];//最大债权金额
            $thirdArr[$key]['creditor'] = $thirdproduct['creditor'];//原始债权人
            $thirdArr[$key]['maxcreditor'] = $thirdproduct['maxcreditor'];//最大债权人

        }
        //过滤掉可投额度为空的债权标
        foreach ($thirdArr as $key => $value) {
            if (empty($value['k_money'])) unset($thirdArr[$key]);
        }
        sort($thirdArr);//重新生成索引下标
        //对数组进行排序处理，让其按照可投金额大小从小到大为主，结束时间从早到晚为辅进行排序
        foreach ($thirdArr as $k => $v) {
            $k_money[$k] = $v['k_money'];
            $end_at[$k] = $v['end_at'];
        }
        array_multisort($k_money, SORT_ASC, $end_at, SORT_ASC, $thirdArr);

        // 产生债权数据字典，把金额分配到最早的，金额最小的一部分债权上。
        $s_money = $money;
        $result = array();
        foreach($thirdArr as $key => $val){
            if( $s_money <= 0){
                break;
            }

            if($s_money >= $val['k_money']){
                $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间

                $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                $result[$key . 'x']['mcmoney'] = $val['mcmoney'];//投资给最大债权人的金额

                $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
                $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                $s_money = $s_money - $val['k_money'];
            }
            else{
                $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间
                //将投资金额分配分配到原始债权人，若原始债权人债权卖完，则将剩余的卖给最大债权人
                if ($val['ocmoney'] >= $s_money) {
                    $result[$key . 'x']['ocmoney'] = $s_money;//投资给原始债权的金额
                    $result[$key . 'x']['mcmoney'] = 0;

                } else {
                    $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                    $result[$key . 'x']['mcmoney'] = $s_money - $val['ocmoney'];//投资给最大债权人的金额
                }

                $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
                $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                $s_money = 0;
            }
        }

//        //生成债权数据字典：比随机生成债权数目少1，生成出可投金额较小的债权项目的对应的投资金额、债权项目id，结束时间。剩余的钱生成给可投金额最大的债权项目，依次类推。
//        $s_money = $money;
//        $i = 0;
//        $result = array();
//        foreach ($thirdArr as $key => $val) {
//            $i++;
//            //消除小数，避免投资钱数分配除不尽,导致钱数减少
//            if ($s_money % ($num - 1) == '0') {
//                $p_money = $s_money / ($num - 1);//取投资钱的平均数
//            } else {
//                $p_money = ceil($s_money / ($num - 1));//取投资钱的平均数
//            }
//            if ($i < $num - 1) {
//                if ($p_money > $val['k_money']) {
//                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
//                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间
//
//                    $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
//                    $result[$key . 'x']['mcmoney'] = $val['mcmoney'];//投资给最大债权人的金额
//
//                    $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
//                    $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人
//
//                    $s_money = $s_money - $val['k_money'];
//                } else {
//                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
//                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间
//                    //将投资金额分配分配到原始债权人，若原始债权人债权卖完，则将剩余的卖给最大债权人
//                    if ($val['ocmoney'] >= $p_money) {
//                        $result[$key . 'x']['ocmoney'] = $p_money;//投资给原始债权的金额
//                        $result[$key . 'x']['mcmoney'] = 0;
//                    } else {
//                        $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
//                        $result[$key . 'x']['mcmoney'] = $p_money - $val['ocmoney'];//投资给最大债权人的金额
//                    }
//
//                    $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
//                    $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人
//
//                    $s_money = $s_money - $p_money;
//                }
//            } else {
//                break;
//            }
//
//        }
//
//        //对数组进行排序处理，让其按照可投金额大小从大到小为主，结束时间从早到晚为辅进行排序
//        foreach ($thirdArr as $k => $v) {
//            $k_money[$k] = $v['k_money'];
//            $end_at[$k] = $v['end_at'];
//        }
//        array_multisort($k_money, SORT_DESC, $end_at, SORT_ASC, $thirdArr);
//
//        //生成债权数据字典：将剩余的钱依次分给到可投金额最大的债权项目中，生成债权数据字典
//        foreach ($thirdArr as $key => $val) {
//            if ($s_money < $val['k_money']) {
//                $result[$key . 'd']['id'] = $val['thirdorder_id'];
//
//                //将投资金额分配分配到原始债权人，若原始债权人债权卖完，则将剩余的卖给最大债权人
//                if ($val['ocmoney'] >= $s_money) {
//                    $result[$key . 'd']['ocmoney'] = $s_money;//投资给原始债权的金额
//                    $result[$key . 'd']['mcmoney'] = 0;
//                } else {
//                    $result[$key . 'd']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
//                    $result[$key . 'd']['mcmoney'] = $s_money - $val['ocmoney'];//投资给最大债权人的金额
//                }
//
//                $result[$key . 'd']['end_at'] = $val['end_at'];
//                $result[$key . 'd']['creditor'] = $val['creditor'];//原始债权人
//                $result[$key . 'd']['maxcreditor'] = $val['maxcreditor'];//最大债权人
//
//                break;
//            } else {
//                $result[$key . 'd']['id'] = $val['thirdorder_id'];
//
//                $result[$key . 'd']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
//                $result[$key . 'd']['mcmoney'] = $val['mcmoney'];//投资给最大债权人的金额
//
//                $result[$key . 'd']['end_at'] = $val['end_at'];
//                $result[$key . 'd']['creditor'] = $val['creditor'];//原始债权人
//                $result[$key . 'd']['maxcreditor'] = $val['maxcreditor'];//最大债权人
//
//                $s_money = $s_money - $val['k_money'];
//            }
//        }

        return $result;

    }


    /**
     * Auther:langxi
     *
     * 将投资的金额按照生成的债权数据字典，分散加入债权表
     */
    private static function set_Third($thirdArr, $member_id)
    {
        //债权表中无需写入购买人数，没有意义
        foreach ($thirdArr as $vo) {
            //获取thirdproduct中的原始债权人id，最大债权人id，并检查其是否为公司员工，并判断资金应支付给债权人还是原始债权人,
            $thirdproduct = Thirdproduct::find()->select(['creditor', 'maxcreditor'])->where(['id' => $vo['id']])->asArray()->one();
            if ($vo['ocmoney'] > 0) {
                //将钱支付给原始债权人（网站）
                $creditor = $thirdproduct['creditor'];
                $is_yuan = Catmiddle::find()->where(['cid' => '2', 'uid' => $creditor])->asArray()->one();
                if (!$is_yuan) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '原始债权人异常',
                        'data' => null,
                    );
                    return $result;
                }
                $yuan_info = Info::findOne($creditor);
                $yuan_info->balance = $yuan_info['balance'] + $vo['ocmoney'];
                $yuan_info = $yuan_info->save();
                if (!$yuan_info) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '资金放入原始债权人账户失败',
                        'data' => null,
                    );
                    return $result;
                }
                //写入职员账户记录表中
                $clerk = new ClerkLog();
                $clerk->member_id = $member_id;
                $clerk->clerk_id = $creditor;
                $clerk->behav = ClerkLog::CLERK_BEHAV_ONE;
                $clerk->step = $vo['ocmoney'];
                $clerk->remark = '原始债权人';
                $clerk = $clerk->save();
                if (!$clerk) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '写入职员账户记录失败',
                        'data' => null,
                    );
                    return $result;
                }

            }
            if ($vo['mcmoney'] > 0) {
                //将钱支付给最大债权人（网站）
                $maxcreditor = $thirdproduct['maxcreditor'];
                $is_max = Catmiddle::find()->where(['cid' => '1', 'uid' => $maxcreditor])->asArray()->one();
                if (!$is_max) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '最大债权人异常',
                        'data' => null,
                    );
                    return $result;
                }
                $max_info = Info::findOne($maxcreditor);
                $max_info->balance = $max_info['balance'] + $vo['mcmoney'];
                $max_info = $max_info->save();
                if (!$max_info) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '资金放入最大债权人账户失败',
                        'data' => null,
                    );
                    return $result;
                }
                //写入职员账户记录表中
                $clerk = new ClerkLog();
                $clerk->member_id = $member_id;
                $clerk->clerk_id = $maxcreditor;
                $clerk->behav = ClerkLog::CLERK_BEHAV_ONE;
                $clerk->step = $vo['mcmoney'];
                $clerk->remark = '最大债权人';
                $clerk = $clerk->save();
                if (!$clerk) {
                    $result = array(
                        'errorNum' => '1',
                        'errorMsg' => '写入职员账户记录失败',
                        'data' => null,
                    );
                    return $result;
                }
            }


            //按用户id和订单时间获取上面生成的订单id值
            $order = Order::find()->where(['member_id' => $member_id])->orderBy('start_at desc')->asArray()->one();
            $thirdorder = new Thirdorder();
            $thirdorder->member_id = $member_id;
            $thirdorder->thirdproduct_id = $vo['id'];
            $thirdorder->order_id = $order['id'];
            $thirdorder->money = $vo['mcmoney'] + $vo['ocmoney'];
            $thirdorder->ocmoney = $vo['ocmoney'];
            $thirdorder->mcmoney = $vo['mcmoney'];
            $thirdorder->start_money = $vo['mcmoney'] + $vo['ocmoney'];

            $thirdorder->status = Thirdorder::STATUS_ACTIVE;
            $thirdorder->start_at = time();
            $thirdorder->end_at = $order['end_at'];
            $thirdorder = $thirdorder->save();
            if (!$thirdorder) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '债权数据字典写入债权订单表失败',
                    'data' => null,
                );
                return $result;
            }

            //将资金分配给债权表
            $thirdproduct = Thirdproduct::findOne($vo['id']);
            $thirdproduct->ocmoney = $thirdproduct['ocmoney'] - $vo['ocmoney'];
            $thirdproduct->mcmoney = $thirdproduct['mcmoney'] - $vo['mcmoney'];
            $thirdproduct->invest_sum = $thirdproduct['invest_sum'] + $vo['ocmoney'] + $vo['mcmoney'];
            $thirdproduct = $thirdproduct->save();
            if (!$thirdproduct) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '资金写入债权表失败',
                    'data' => null,
                );
                return $result;
            }
        }
        return false;
    }

    /**
     * Auther:langxi
     *
     *更新总投资记录
     */
    private static function total_log($member_id, $money)
    {
        $order = Order::find()->where(['member_id' => $member_id])->asArray()->count();
        if ($order > 1) {
            $common = Common::findOne('1');
            $common->invest_sum = $common['invest_sum'] + $money;
            $common->invest_times = $common['invest_times'] + 1;
            $common = $common->save();
        } else {
            $common = Common::findOne('1');
            $common->invest_sum = $common['invest_sum'] + $money;
            $common->invest_people = $common['invest_people'] + 1;
            $common->invest_times = $common['invest_times'] + 1;
            $common = $common->save();
        }
        if (!$common) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '更新总投资记录失败',
                'data' => null,
            );
            return $result;
        } else {
            return false;
        }

    }

    /**
     * Auther:langxi
     *
     * 查看用户之前是否购买过该项目
     */
    private static function check_people($member_id, $product_id)
    {
        //用户购买该项目的订单数量为1则返回true,大于1则返回false
        $order = Order::find()->where(['member_id' => $member_id, 'product_id' => $product_id])->asArray()->count();
        if ($order > 1) {
            return false;
        }
        return true;
    }


    /**
     * Auther:langxi
     *
     * 检测项目状态 金额是否满足项目要求
     * @param $product_id
     * @param $money
     * @return bool
     * @throws ErrorException
     */
    private static function checkProduct($product_id, $money)
    {
        if (!$product_id || !is_numeric($product_id) || !is_int($product_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if (!$product) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该项目暂无',
                'data' => null,
            );
            return $result;
        }

        //项目是否被锁定
        if ($product['status'] == Product::STATUS_LOCK) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '项目已售罄',
                'data' => null,
            );
            return $result;
        }

        //投资是否开始或过期
        $stat_time = $product['start_at'];
        $end_time = $product['end_at'];
        $now_time = time();
        if ($now_time < $stat_time) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该项目尚未开售',
                'data' => null,
            );
            return $result;
        }
        if ($now_time > $end_time) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该项目已下架',
                'data' => null,
            );
            return $result;
        }


        //投资金额是否大于项目剩余额度
        $product_money = $product['invest_sum'] + $money;
        $total_money = $product['amount'];
        if ($product['invest_sum'] == $product['amount']) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该项目已卖完',
                'data' => null,
            );
            return $result;
        }
        if ($product_money > $total_money) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '您投资的金额大于该项目剩余的额度',
                'data' => null,
            );
            return $result;
        }
        return false;
    }

    /**
     * Auther:langxi
     *
     * 检测投资金额是否符合项目的最大额度和最小额度以及为整数的要求
     * @return bool
     */
    private static function checkMoney($product_id, $money)
    {
        if (!$product_id || !is_numeric($product_id) || !is_int($product_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if (!$product) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '该项目暂无',
                'data' => null,
            );
            return $result;
        }
        if (!$money || !is_numeric($money)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '金额不符合规范',
                'data' => null,
            );
            return $result;
        }
//        $Integer = is_Integer(intval($money));
//        if (!$Integer) {
//            $result = array(
//                'errorNum' => '1',
//                'errorMsg' => '投资金额必须为整数',
//                'data' => null,
//            );
//            return $result;
//        }
        /* $product = Product::find()->where(['id' => $product_id])->asArray()->one();
         $each_min = $product['each_min'];
         $each_max = $product['each_max'];
         $k_money = $product['amount'] - $product['invest_sum'];
         if ($money > $each_max) {
             $result = array(
                 'errorNum' => '1',
                 'errorMsg' => '用户投资额度大于项目允许的每次最大投资额度',
                 'data' => null,
             );
             return $result;
         }
         if ($k_money > $each_min) {
             if ($money < $each_min) {
                 $result = array(
                     'errorNum' => '1',
                     'errorMsg' => '用户投资额度小于项目允许的每次最小投资额度',
                     'data' => null,
                 );
                 return $result;
             }
         }*/
        return false;
    }


    /**
     *Auther:langxi
     *
     * 检测一日投资次数，投资金额
     * @param $member_id
     * @param $money
     * @return bool
     * @throws ErrorException
     */
    private static function check_invest($member_id, $product_id, $money)
    {
        //检测用户投资次数
        $bankTimes = self::investTimes($member_id);
        $times = self::investkTime();
        if ($bankTimes >= $times) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户投资次数达到限制',
                'data' => null,
            );
            return $result;
        }
        //检测用户投资金额
        //$lastTotal = self::investTotal($member_id);
        // $cashTotal = $lastTotal + $money;
        $cash = self::investcash();
        if ($money > $cash) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户投资金额超过了每次允许的最大限额',
                'data' => null,
            );
            return $result;
        }
        //若账户余额大于最小投资额度，则判断投资金额是否大于最小投资金额
        /*$k_money = Product::find()->where(['id'=>$product_id])->asArray()->one();
        $k_money = $k_money['amount'] - $k_money['invest_num'];
        $mincash = self::investminmoney();
        if($k_money > $mincash){
            if($mincash > $money){
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '对不起，您的投资金额小于最小投资额度'.$mincash,
                    'data' => null,
                );
                return $result;
            }
        }*/
        $result = array(
            'errorNum' => 0,
            'errorMsg' => 'success',
            'data' => null,
        );
        return $result;
    }


    /**
     *Auther:langxi
     *status表示行为状态,step:金额
     * 用户一日内投资总金额
     * @param $member_id
     * @return mixed
     */
    private static function investTotal($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_INVEST_SUC, 'action' => 'Invest/invest'])
            ->andWhere(['>=', 'create_at', $min_time])
            ->andWhere(['<', 'create_at', $max_time])
            ->sum('step');
    }

    /**
     *Auther:langxi
     *
     * 用户一日内投资次数
     * @param $member_id
     * @return int|string
     */
    private static function investTimes($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_INVEST_SUC, 'action' => 'Invest/invest'])
            ->andWhere(['>=', 'create_at', $min_time])
            ->andWhere(['<', 'create_at', $max_time])
            ->count('member_id');

    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日投资最大金额
     * @return string
     */
    private static function investcash()
    {
        $result = AssetConfig::find()->select(['invest_max'])->asArray()->one();
        if ($result) {
            $result = $result['invest_max'];
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取允许用户每日的最大投资金额失败',
                'data' => null,
            );
            return $result;
        }
        return $result;
    }


    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日赎回最小金额
     * @return string
     */
    private static function investminmoney()
    {
        $result = AssetConfig::find()->select(['invest_min'])->asArray()->one();
        if ($result) {
            $result = $result['invest_min'];
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取允许用户每日的最小投资金额失败',
                'data' => null,
            );
            return $result;
        }
        return $result;
    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日允许的最大投资次数
     * @return string
     */
    private static function investkTime()
    {
        $result = AssetConfig::find()->select(['invest_num'])->asArray()->one();
        if ($result) {
            $result = $result['invest_num'];
        } else {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '获取允许用户每日的最大投资次数失败',
                'data' => null,
            );
            return $result;
        }

        return $result;
    }


    /**
     *Auther:langxi
     *
     *检测用户状态 银行卡手机号 真实姓名身份证号  及是否被锁定
     * @param $member_id
     * @return bool
     * @throws ErrorException
     */
    private static function checkMember($member_id)
    {
        if (!is_numeric($member_id) || !is_int($member_id)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '参数错误',
                'data' => null,
            );
            return $result;
        }
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        if (!$card) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '此账户不存在',
                'data' => null,
            );
            return $result;
        } else {
            if (empty($card['bank_card']) || empty($card['bank_card_phone'])) {
                $result = array(
                    'errorNum' => '1',
                    'errorMsg' => '银行卡或银行卡对应手机号不存在',
                    'data' => null,
                );
                return $result;
            }
        }
        $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
        if (!$member) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户不存在',
                'data' => null,
            );
            return $result;
        }
        $real_name = $member['real_name'];
        $idcard = $member['idcard'];
        if (empty($real_name) || empty($idcard)) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '真实姓名或身份证号不存在',
                'data' => null,
            );
            return $result;
        }
        $status = $member['lock'];
        if ($status != UcenterMember::TYPE_UNLOCK) {
            $result = array(
                'errorNum' => '1',
                'errorMsg' => '用户被锁定',
                'data' => null,
            );
            return $result;
        }
        return false;
    }


}