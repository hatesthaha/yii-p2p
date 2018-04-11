<?php
/**
 * Created by PhpStorm.
 * Auther: langxi
 * Date: 2015/7/11
 * Time: 5:00
 * 用户投资
 */
namespace frontend\actions;

use common\models\base\asset\ClerkLog;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\experience\Gold;
use common\models\base\experience\Rule;
use common\models\base\fund\Common;
use common\models\base\fund\product;
use common\models\base\fund\order;
use common\models\base\fund\Thirdorder;
use common\models\base\fund\Thirdproduct;
use common\models\base\fund\ProductThirdproduct;
use common\models\base\ucenter\Catmiddle;
use yii\base\ErrorException;
use common\models\UcenterMember;
use yii;
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
        return $common;
    }

    /**
     * Auther:langxi
     *
     * 获取项目列表
     */
    public static function product_list($page)
    {
        $p = 2;//每页几条
        $num = $p * ($page - 1);//偏移条数
        //按id排序
        $product = Product::find()->andwhere(['<>', 'status', Product::STATUS_LOCK])->andWhere(['>', 'create_at', 1441641600])->orderBy('id desc')->limit($p)->offset($num)->asArray()->all();
        if (!$product) {
            throw new ErrorException('暂无项目', 1001);
        }
        $lists = array();
        foreach ($product as $key => $value) {
            $lists[$key]['id'] = $value['id'];
            $lists[$key]['title'] = $value['title'];
            $lists[$key]['intro'] = $value['intro'];
            $lists[$key]['amount'] = $value['amount'];
            $lists[$key]['rate'] = $value['rate'];
            $lists[$key]['invest_people'] = $value['invest_people'];
            $lists[$key]['invest_sum'] = $value['invest_sum'];
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


        return $lists;
    }

    /**
     * Auther:langxi
     *
     * 返回项目详细信息
     */
    public static function xproduct($product_id)
    {
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        return $product;
    }

    /**
     * Auther:langxi
     *
     * 返回项目的可投金额
     */
    public static function kmoney($product_id)
    {
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        $product_money = $product['amount'] - $product['invest_sum'];
        return $product_money;
    }

    /**
     * Auther:langxi
     *
     * 返回用户的投资记录
     */
    public static function InvestLog($member_id)
    {
        if (!$member_id) {
            throw new ErrorException('参数错误', 1001);
        }
        $is_user = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
        if (!$is_user) {
            throw new ErrorException('该用户不存在', 1001);
        }
        $log = Log::find()->andWhere(['member_id' => $member_id])->andWhere(['>', 'status', '0'])->asArray()->all();
        if (!$log) throw new ErrorException('该用户暂无记录');

        return $log;
    }

    /**
     * Auther:langxi
     *
     *体验金
     */
    public static function gold($member_id, $money)
    {
        $order = Order::find()->select(['id'])->where(['member_id' => $member_id])->andWhere(['>=', 'start_money', '1000'])->asArray()->all();
        $is_order = count($order);
        //投资金额不小于1000且只有一个金额不小于1000的订单（含刚生成的订单）：首次投资大于1000
        if ($money >= 1000 && $is_order == 1) {
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
                $gold = $gold->save();

            }

            //判断是否有邀请人
            $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
            if ($member['parent_member_id']) {
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

        //判断用户是否可进行提现操作
        $is_go = Info::find()->select(['status'])->where(['member_id' => $member_id])->asArray()->one();
        if ($is_go['status'] > 0) {
            throw new ErrorException('处理中，请稍后再试');
        }
        Info::updateAll(['status' => Info::GO_TWO], ['member_id' => $member_id]);//进行操作，状态变为投资处理中

        //检测用户投资资金，次数
        $check_invest = self::check_invest($member_id, $product_id, $money);
        if ($check_invest) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException($check_invest);
        }


        //检测用户账户余额是否满足投资金额
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        if ($card['balance'] < $money) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException('账户金额不足，请进行充值', 6001);
        }
        //检测投资金额是否满足项目的每次投资最大最小额度限制
        $checkMoney = self::checkMoney($product_id, $money);
        if ($checkMoney) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException($checkMoney);
        }
        //检测用户状态
        $checkMember = self::checkMember($member_id);
        if ($checkMember) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException($checkMember);
        }
        //检测项目状态
        $checkProduct = self::checkProduct($product_id, $money);
        if ($checkProduct) {
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException($checkProduct);
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
                throw new ErrorException('投资失败', 6002);
            }

            if ($product['type'] == Product::TYPE_THIRD) {
                //进行投资将钱放入order中  债权转让项目订单（一对多）
                $order = new Order();
                $order->member_id = $member_id;//用户id
                $order->product_id = $product_id;//投资项目id
                $order->type = Product::TYPE_THIRD;//投资项目类型
                $order->money = $money;//剩余投资金额
                $order->start_money = $money;//最初投资金额
                $order->status = Order::STATUS_ACTIVE;//订单状态
                $order->start_at = time();//订单生效时间
                $order->end_at = $end_at;
                $order = $order->save();
                if (!$order) {
                    throw new ErrorException('投资失败', 6002);
                }
            } else {
                //进行投资将钱放入order中  type=0债权项目（一对一）
                $order = new Order();
                $order->member_id = $member_id;//用户id
                $order->product_id = $product_id;//投资项目id
                $order->type = Product::TYPE_PRO;//投资项目类型
                $order->money = $money;//剩余投资金额
                $order->start_money = $money;//最初投资金额
                $order->status = Order::STATUS_ACTIVE;//订单状态
                $order->start_at = time();//订单生效时间
                $order->end_at = $end_at;
                $order = $order->save();
                if (!$order) {
                    throw new ErrorException('投资失败', 6002);
                }
            }

            //检测用户是否投资过该项目，进行项目投资人数加一处理，项目已投金额增长
            $check_people = self::check_people($member_id, $product_id);
            if ($check_people) {
                $product = Product::find()->where(['id' => $product_id])->asArray()->one();
                $product_money = $product['invest_sum'] + $money;
                $total_money = $product['amount'];
                if ($product_money > $total_money) {
                    throw new ErrorException('您投资的金额大于该项目剩余的额度', 6001);
                }
                $product = Product::findOne($product_id);
                $product->invest_sum = $product['invest_sum'] + $money;
                if ($product['invest_sum'] == $product['amount']) {
                    $product->status = Product::STATUS_OUT;
                }
                $product->invest_people = $product['invest_people'] + 1;
                $res = $product->save();

                if (!$res) {
                    throw new ErrorException('项目已投金额、投资人数增加失败', 6002);
                }
            } else {
                $product = Product::find()->where(['id' => $product_id])->asArray()->one();
                $product_money = $product['invest_sum'] + $money;
                $total_money = $product['amount'];
                if ($product_money > $total_money) {
                    throw new ErrorException('您投资的金额大于该项目剩余的额度', 6001);
                }
                $product = Product::findOne($product_id);
                $product->invest_sum = $product['invest_sum'] + $money;
                if ($product['invest_sum'] == $product['amount']) {
                    $product->status = Product::STATUS_OUT;

                }
                $res = $product->save();
                if (!$res) {
                    throw new ErrorException('项目已投金额增加失败', 6002);
                }
            }

            //type=Product::TYPE_THIRD为一对多项目债权转让

            //获取用户此次投资此项目的债权字典
            $thirdArr = self::creditor_dic($product_id, $money);
            //按照生成的债权字典，将钱分配给债权表
            $setthird = self::set_Third($thirdArr, $member_id);

            if (!$setthird) {
                throw new ErrorException('资金分配到债权失败', 6002);
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
                throw new ErrorException('删减冻结金额失败', 6002);
            }


            //投资成功进行投资记录
            $assetlog = new Log();
            $assetlog->member_id = $member_id;
            $assetlog->product_id = $product_id;
            $assetlog->step = $money;
            $assetlog->action = 'Invest/invest';
            $assetlog->status = self::INVESTSUCCEED;//2标识投资成功
            $assetlog->bankcard = $bank_card;
            $assetlog->remark = '网站投资成功';
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
            //更新网站交易记录
            Log::updateAll(['trade_no' => $sina_invest['data']['out_trade_no'],'remark' => '用户投资，新浪代收成功'],['id' => $assetlog['id']]);

            $sina_peyee = sinapay::batchPay($sina_pay, $sina_invest['data']['out_trade_no']);//托管批量代付接口


            if ($sina_peyee['errorNum']) {
                $sina_refund = sinapay::hostingRefund($sina_invest['data']['identity_id'], $sina_invest['data']['out_trade_no'], $sina_invest['data']['money']);
                if ($sina_refund['errorNum']) {
                    throw new ErrorException($sina_refund['errorMsg'], 7001);
                }
                throw new ErrorException($sina_peyee['errorMsg'], 7001);
            }

            //更新网站交易记录
            Log::updateAll(['remark' => '用户投资成功'],['id' => $assetlog['id']]);
            //总投资记录
            self::total_log($member_id, $money);


            $transaction->commit();
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            return true;
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
            Info::updateAll(['status' => '0'], ['member_id' => $member_id]);
            throw new ErrorException($remark, 6002);


        }

    }


    /**
     * Auther:langxi
     *
     * 生成债权字典：生成用户投资金额随机分配给几个债权项目（现为3-5个）,并随机出钱数，按照钱数筛选债权项目，进行分配
     */
    private static function creditor_dic($product_id, $money)
    {
        $num = ceil(mt_rand(3000, 6000) / 1000);//生成随机数

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

        //生成债权数据字典：比随机生成债权数目少1，生成出可投金额较小的债权项目的对应的投资金额、债权项目id，结束时间。剩余的钱生成给可投金额最大的债权项目，依次类推。
        $s_money = $money;
        $i = 0;
        $result = array();
        foreach ($thirdArr as $key => $val) {
            $i++;
            //消除小数，避免投资钱数分配除不尽,导致钱数减少
            if ($s_money % ($num - 1) == '0') {
                $p_money = $s_money / ($num - 1);//取投资钱的平均数
            } else {
                $p_money = ceil($s_money / ($num - 1));//取投资钱的平均数
            }
            if ($i < $num - 1) {
                if ($p_money > $val['k_money']) {
                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间

                    $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                    $result[$key . 'x']['mcmoney'] = $val['mcmoney'];//投资给最大债权人的金额

                    $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
                    $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                    $s_money = $s_money - $val['k_money'];
                } else {
                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间
                    //将投资金额分配分配到原始债权人，若原始债权人债权卖完，则将剩余的卖给最大债权人
                    if ($val['ocmoney'] >= $p_money) {
                        $result[$key . 'x']['ocmoney'] = $p_money;//投资给原始债权的金额
                        $result[$key . 'x']['mcmoney'] = 0;
                    } else {
                        $result[$key . 'x']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                        $result[$key . 'x']['mcmoney'] = $p_money - $val['ocmoney'];//投资给最大债权人的金额
                    }

                    $result[$key . 'x']['creditor'] = $val['creditor'];//原始债权人
                    $result[$key . 'x']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                    $s_money = $s_money - $p_money;
                }
            } else {
                break;
            }

        }

        //对数组进行排序处理，让其按照可投金额大小从大到小为主，结束时间从早到晚为辅进行排序
        foreach ($thirdArr as $k => $v) {
            $k_money[$k] = $v['k_money'];
            $end_at[$k] = $v['end_at'];
        }
        array_multisort($k_money, SORT_DESC, $end_at, SORT_ASC, $thirdArr);

        //生成债权数据字典：将剩余的钱依次分给到可投金额最大的债权项目中，生成债权数据字典
        foreach ($thirdArr as $key => $val) {
            if ($s_money < $val['k_money']) {
                $result[$key . 'd']['id'] = $val['thirdorder_id'];

                //将投资金额分配分配到原始债权人，若原始债权人债权卖完，则将剩余的卖给最大债权人
                if ($val['ocmoney'] >= $s_money) {
                    $result[$key . 'd']['ocmoney'] = $s_money;//投资给原始债权的金额
                    $result[$key . 'd']['mcmoney'] = 0;
                } else {
                    $result[$key . 'd']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                    $result[$key . 'd']['mcmoney'] = $s_money - $val['ocmoney'];//投资给最大债权人的金额
                }

                $result[$key . 'd']['end_at'] = $val['end_at'];
                $result[$key . 'd']['creditor'] = $val['creditor'];//原始债权人
                $result[$key . 'd']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                break;
            } else {
                $result[$key . 'd']['id'] = $val['thirdorder_id'];

                $result[$key . 'd']['ocmoney'] = $val['ocmoney'];//投资给原始债权人的金额
                $result[$key . 'd']['mcmoney'] = $val['mcmoney'];//投资给最大债权人的金额

                $result[$key . 'd']['end_at'] = $val['end_at'];
                $result[$key . 'd']['creditor'] = $val['creditor'];//原始债权人
                $result[$key . 'd']['maxcreditor'] = $val['maxcreditor'];//最大债权人

                $s_money = $s_money - $val['k_money'];
            }
        }

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
                    throw new ErrorException('原始债权人异常');
                }
                $yuan_info = Info::findOne($creditor);
                $yuan_info->balance = $yuan_info['balance'] + $vo['ocmoney'];
                $yuan_info = $yuan_info->save();
                if (!$yuan_info) {
                    throw new ErrorException('资金放入原始债权人账户失败');
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
                    throw new ErrorException('写入职员账户记录失败');
                }
            }
            if ($vo['mcmoney'] > 0) {
                //将钱支付给最大债权人（网站）
                $maxcreditor = $thirdproduct['maxcreditor'];
                $is_max = Catmiddle::find()->where(['cid' => '1', 'uid' => $maxcreditor])->asArray()->one();
                if (!$is_max) {
                    throw new ErrorException('最大债权人异常');
                }
                $max_info = Info::findOne($maxcreditor);
                $max_info->balance = $max_info['balance'] + $vo['mcmoney'];
                $max_info = $max_info->save();
                if (!$max_info) {
                    throw new ErrorException('资金放入最大债权人账户失败');
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
                    throw new ErrorException('写入职员账户记录失败1');
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
            if (!$thirdorder) throw new ErrorException('债权数据字典写入债权订单表失败', 6002);

            //将资金分配给债权表
            $thirdproduct = Thirdproduct::findOne($vo['id']);
            $thirdproduct->ocmoney = $thirdproduct['ocmoney'] - $vo['ocmoney'];
            $thirdproduct->mcmoney = $thirdproduct['mcmoney'] - $vo['mcmoney'];
            $thirdproduct->invest_sum = $thirdproduct['invest_sum'] + $vo['ocmoney'] + $vo['mcmoney'];
            $thirdproduct = $thirdproduct->save();
            if (!$thirdproduct) {
                throw new ErrorException('资金写入债权表失败', 6002);
            }
        }
        return true;
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
            throw new ErrorException('更新总投资记录失败', 6002);
        } else {
            return true;
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
        $product_id = (int)$product_id;
        if (!$product_id || !is_numeric($product_id) || !is_int($product_id)) {
            $result = '参数错误';
            return $result;
        }
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if (!$product) {
            $result = '该项目不存在';
            return $result;
        }

        //项目是否被锁定
        if ($product['status'] == Product::STATUS_LOCK) {
            $result = '项目已售罄';
            return $result;
        }

        //投资是否开始或过期
        $stat_time = $product['start_at'];
        $end_time = $product['end_at'];
        $now_time = time();
        if ($now_time < $stat_time) {
            $result = '该项目尚未开售';
            return $result;
        }
        if ($now_time > $end_time) {
            $result = '该项目已下架';
            return $result;
        }

        //投资金额是否大于项目剩余额度
        $product_money = $product['invest_sum'] + $money;
        $total_money = $product['amount'];
        if ($product['invest_sum'] == $product['amount']) {
            $result = '该项目已卖完';
            return $result;
        }
        if ($product_money > $total_money) {
            $result = '您投资的金额大于该项目剩余的额度';
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
        $product_id = (int)$product_id;
        if (!$product_id || !is_numeric($product_id) || !is_int($product_id)) {
            $result = '参数错误';
            return $result;
        }
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if (!$product) {
            $result = '该项目不存在';
            return $result;
        }
        $money = intval($money);

//        if (!$money || !is_numeric($money) || !is_int($money)) {
//            $result = '投资金额必须为整数';
//            return $result;
//        }
        /*$product = Product::find()->where(['id' => $product_id])->asArray()->one();
        $each_min = $product['each_min'];
        $each_max = $product['each_max'];
        $k_money = $product['amount'] - $product['invest_sum'];
        if ($money > $each_max) {
            $result = '用户投资额度大于项目允许的每次最大投资额度';
            return $result;
        }
        if ($k_money > $each_min) {
            if ($money < $each_min) {
                $result = '用户投资额度小于项目允许的每次最小投资额度';
                return $result;
            }
        }*/
        return false;
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
        $member_id = (int)$member_id;
        if (!is_numeric($member_id)) {
            $result = '参数错误';
            return $result;
        }
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        if (!$card) {
            $result = '此账户不存在';
            return $result;
        } else {
            if (empty($card['bank_card']) || empty($card['bank_card_phone'])) {
                $result = '银行卡或银行卡对应手机号不存在';
                return $result;
            }
        }
        $member = UcenterMember::find()->where(['id' => $member_id])->asArray()->one();
        if (!$member) {
            $result = '用户不存在';
            return $result;
        }
        $real_name = $member['real_name'];
        $idcard = $member['idcard'];
        if (empty($real_name) || empty($idcard)) {
            $result = '真实姓名或身份证号不存在';
            return $result;
        }
        $status = $member['lock'];
        if ($status != UcenterMember::TYPE_UNLOCK) {
            $result = '用户被锁定';
            return $result;
        }
        return false;
    }


    //再投资
    public static function Re_invest()
    {
        //遍历thirdproduct表获取到昨天到期的thirdproduct项目的id
        $max_time = strtotime(date('Y-m-d'));
        $min_time = strtotime(date('Y-m-d', strtotime('-1 day')));
        $thirdproduct = (new \yii\db\Query())
            ->select(['id'])
            ->from('fund_thirdproduct')
            ->andWhere(['>=', 'end_at', $min_time])
            ->andWhere(['<', 'end_at', $max_time])
            ->all();
        $thirdorderArr = array();

        //根据thirdproduct_id在thirdorder表中获取到order_id
        foreach ($thirdproduct as $key => $value) {
            $thirdorder = (new \yii\db\Query())
                ->groupBy('order_id')
                ->select(['order_id'])
                ->from('fund_thirdorder')
                ->where(['thirdproduct_id' => $value['id']])
                ->all();

            foreach ($thirdorder as $k => $v) {
                $thirdorderArr[$key . 'O' . $k]['order_id'] = $v['order_id'];
            }


        }
        $thirdorderArr = self::a_array_unique($thirdorderArr);


        //根据order_id在order表中获取到member_id,money,
        foreach ($thirdorderArr as $k => $v) {
            $order = (new \yii\db\Query())
                ->select(['id', 'member_id', 'money'])
                ->from('fund_orders')
                ->where(['id' => $v['order_id']])
                ->one();

            $order_id = $order['id'];
            $member_id = $order['member_id'];
            $money = $order['money'];


            //判断已购买且未被使用且未过期的债权项目中的可投资额度是否大于订单金额，若小于则不进行在投资，若大于则进行再投资
            $amount = Thirdproduct::find()
                ->andWhere(['process_status' => 0, 'status' => Thirdproduct::STATUS_ACTIVE, 'intent' => Thirdproduct::INTENT_CHECK])
                ->andWhere(['>=', 'end_at', time()])
                ->asArray()->sum('amount');
            $invest_sum = Thirdproduct::find()
                ->andWhere(['process_status' => 0, 'status' => Thirdproduct::STATUS_ACTIVE, 'intent' => Thirdproduct::INTENT_CHECK])
                ->andWhere(['>=', 'end_at', time()])
                ->asArray()->sum('invest_sum');
            $cha = $amount - $invest_sum;
            if ($cha > $money) {
                //对thirdorder中order_id等于id的订单进行软删除处理
                //Thirdorder::deleteAll(['order_id' => $order_id, 'member_id' => $member_id]);
                Thirdorder::updateAll(['status' => \common\models\base\fund\Thirdorder::STATUS_DELETED], ['order_id' => $order_id, 'member_id' => $member_id]);

                //根据member_id,money,以及order表的id重新生成thirdorder订单
                //获取用户再次投资生成的债权字典
                $thirdArr = self::redoc($money);

                //按照生成的债权字典，将钱分配给债权表
                $setthird = self::reset_Third($thirdArr, $member_id, $order_id);

                if (!$setthird) {
                    throw new ErrorException('资金分配到债权失败', 6002);
                }
            }


        }

    }

    /**
     * 二维数组除去重复的值
     * @param $array
     * @return array
     */
    private static function a_array_unique($array)//写的比较好
    {
        $out = array();
        foreach ($array as $key => $value) {
            if (!in_array($value, $out)) {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    //再次投资生成债权字典
    private static function redoc($money)
    {
        $num = ceil(mt_rand(3000, 6000) / 1000);//生成随机数

        //取已购买、未被使用且未过期的债权项目中的可投资额度
        $productthirdproduct = Thirdproduct::find()
            ->andWhere(['process_status' => 0, 'status' => Thirdproduct::STATUS_ACTIVE, 'intent' => Thirdproduct::INTENT_CHECK])
            ->andWhere(['>=', 'end_at', time()])
            ->asArray()->all();

        $thirdArr = array();
        foreach ($productthirdproduct as $key => $val) {
            //获取债权项目的id,投资总额，已投资总额
            $thirdproduct = Thirdproduct::find()->where(['id' => $val['id']])->asArray()->one();
            $thirdArr[$key]['thirdorder_id'] = $thirdproduct['id']; //债权订单id
            $thirdArr[$key]['k_money'] = $thirdproduct['amount'] - $thirdproduct['invest_sum']; //债权表剩余的可投金额
            $thirdArr[$key]['end_at'] = $thirdproduct['end_at']; //债权标结束时间
        }

        //过滤掉可投额度为空的债权标
        foreach ($thirdArr as $key => $value) {
            if (in_array('0', $value)) unset($thirdArr[$key]);
        }
        sort($thirdArr);//重新生成索引下标
        //对数组进行排序处理，让其按照可投金额大小从小到大为主，结束时间从早到晚为辅进行排序
        foreach ($thirdArr as $k => $v) {
            $k_money[$k] = $v['k_money'];
            $end_at[$k] = $v['end_at'];
        }

        array_multisort($k_money, SORT_ASC, $end_at, SORT_ASC, $thirdArr);

        //生成债权数据字典：比随机生成债权数目少1，生成出可投金额较小的债权项目的对应的投资金额、债权项目id，结束时间。剩余的钱生成给可投金额最大的债权项目，依次类推。
        $s_money = $money;
        $i = 0;
        $result = array();

        foreach ($thirdArr as $key => $val) {

            $i++;
            //消除小数，避免投资钱数分配除不尽,导致钱数减少
            if ($s_money % ($num - 1) == '0') {
                $p_money = $s_money / ($num - 1);//取投资钱的平均数
            } else {
                $p_money = ceil($s_money / ($num - 1));//取投资钱的平均数
            }
            if ($i < $num - 1) {
                if ($p_money > $val['k_money']) {
                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                    $result[$key . 'x']['t_money'] = $val['k_money']; //投资的钱数
                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间

                    $s_money = $s_money - $val['k_money'];
                } else {
                    $result[$key . 'x']['id'] = $val['thirdorder_id']; //投资的债权项目id
                    $result[$key . 'x']['t_money'] = $p_money; //投资的钱数
                    $result[$key . 'x']['end_at'] = $val['end_at']; //投资项目的结束时间
                    $s_money = $s_money - $p_money;
                }
            } else {
                break;
            }


        }

        //对数组进行排序处理，让其按照可投金额大小从大到小为主，结束时间从早到晚为辅进行排序
        foreach ($thirdArr as $k => $v) {
            $k_money[$k] = $v['k_money'];
            $end_at[$k] = $v['end_at'];
        }
        array_multisort($k_money, SORT_DESC, $end_at, SORT_ASC, $thirdArr);

        //生成债权数据字典：将剩余的钱依次分给到可投金额最大的债权项目中，生成债权数据字典
        foreach ($thirdArr as $key => $val) {
            if ($s_money < $val['k_money']) {
                $result[$key . 'd']['id'] = $val['thirdorder_id'];
                $result[$key . 'd']['t_money'] = $s_money;
                $result[$key . 'd']['end_at'] = $val['end_at'];
                break;
            } else {
                $result[$key . 'd']['id'] = $val['thirdorder_id'];
                $result[$key . 'd']['t_money'] = $val['k_money'];
                $result[$key . 'd']['end_at'] = $val['end_at'];
                $s_money = $s_money - $val['k_money'];

            }

        }
        return $result;

    }

    /**
     *Auther:langxi
     *
     * 检测一日赎回次数，赎回金额
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
            $result = '用户投资次数达到限制';
            return $result;
        }
        //检测用户投资金额
        //$lastTotal = self::investTotal($member_id);
        //$cashTotal = $lastTotal + $money;
        $cash = self::investcash();
        if ($money > $cash) {
            $result = '用户投资金额超过了每次允许的最大限额';
            return $result;
        }
        /*//若账户余额大于最小投资额度，则判断投资金额是否大于最小投资金额
        $k_money = Product::find()->where(['id'=>$product_id])->asArray()->one();
        $k_money = $k_money['amount'] - $k_money['invest_num'];
        $mincash = self::investminmoney();
        if($k_money > $mincash){
            if($mincash > $money){
                $result = '对不起，您的投资金额小于最小投资额度';
                return $result;
            }
        }*/


        return false;
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
     * 获取系统规定的用户每日赎回最大金额
     * @return string
     */
    private static function investcash()
    {
        $result = AssetConfig::find()->select(['invest_max'])->asArray()->one();
        if ($result) {
            $result = $result['invest_max'];
        } else {
            throw new ErrorException('获取允许用户每日的最大投资金额失败', 6002);
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
            throw new ErrorException('获取允许用户每日的最小投资金额失败', 6002);
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
            throw new ErrorException('获取允许用户每日的最大投资次数失败', 6002);
        }

        return $result;
    }


    /**
     * Auther:langxi
     *
     * 将再投资的金额按照生成的在投资债权数据字典，分散加入债权表
     */
    private static function reset_Third($thirdArr, $member_id, $order_id)
    {
        //将软删除的订单的金额，从thirdproduct表中的已投金额中减去
        $thirdorder = (new \yii\db\Query())
            ->select(['thirdproduct_id', 'money'])
            ->from('fund_thirdorder')
            ->where(['member_id' => $member_id, 'order_id' => $order_id])
            ->all();

        foreach ($thirdorder as $key => $val) {
            $thirdproduct = Thirdproduct::findOne($val['thirdproduct_id']);
            $thirdproduct->invest_sum = $thirdproduct['invest_sum'] - $val['money'];
            $thirdproduct = $thirdproduct->save();
        }


        //债权表中无需写入购买人数，没有意义
        foreach ($thirdArr as $vo) {
            //将债权数据字典连同订单号写入到thirdorder中
            //按用户id和订单时间获取上面生成的订单id值
            $order = Order::find()->where(['member_id' => $member_id, 'id' => $order_id])->asArray()->one();
            $thirdorder = new Thirdorder();
            $thirdorder->member_id = $member_id;
            $thirdorder->thirdproduct_id = $vo['id'];
            $thirdorder->order_id = $order['id'];
            $thirdorder->money = $vo['t_money'];
            $thirdorder->start_money = $vo['t_money'];
            $thirdorder->status = Thirdorder::STATUS_ACTIVE;
            $thirdorder->start_at = time();
            $thirdorder->end_at = $order['end_at'];
            $thirdorder = $thirdorder->save();
            if (!$thirdorder) throw new ErrorException('债权数据字典写入债权订单表失败', 6002);

            //将资金分配给债权表
            $thirdproduct = Thirdproduct::findOne($vo['id']);
            $thirdproduct->invest_sum = $thirdproduct['invest_sum'] + $vo['t_money'];
            $thirdproduct = $thirdproduct->save();
            if (!$thirdproduct) {
                throw new ErrorException('资金写入债权表失败', 6002);
            }
        }
        return true;
    }


}