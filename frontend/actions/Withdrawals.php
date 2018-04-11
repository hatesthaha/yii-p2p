<?php
/**
 * Created by PhpStorm.
 * Auther: langxi
 * Date: 2015/7/10
 * Time: 9:00
 * 用户提现
 */
namespace frontend\actions;

use common\models\base\activity\RaiseCard;
use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\base\fund\Order;
use common\models\base\fund\Product;
use common\models\base\fund\ProductThirdproduct;
use common\models\base\fund\Thirdorder;
use common\models\base\fund\Thirdproduct;
use common\models\base\setting\Setting;
use common\models\invation\AssetConfig;
use common\models\sinapay\SinaConfig;
use yii\base\ErrorException;
use common\models\UcenterMember;
use common\models\base\ucenter\Catmiddle;
use common\models\base\asset\ClerkLog;

class Withdrawals extends Action
{
    const  RSUCCEED = 3; //赎回成功状态码
    const  RERROR = -3; //赎回失败状态码
    const  WSUCCEED = 4; //提现成功状态码
    const  WERROR = -4; //提现失败状态码

    /**
     * *Auther:langxi
     * $member_id：前台用户id
     * 显示用户银行卡
     * @param $member_id
     * @return array|Asset|mixed|null
     * @throws ErrorException
     */
    public static function showCard($member_id)
    {
        //用户状态检测
        self::checkMember($member_id);
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $card = $card['bank_card'];
        return $card;
    }


    /**
     *Auther:langxi
     *
     * 显示用户账户余额
     * @param $member_id
     * @return array|Asset|mixed|null
     * @throws ErrorException
     */
    public static function showBlance($member_id)
    {
        //用户状态检测
        self::checkMember($member_id);
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $card = $card['balance'];
        return $card;
    }

    /**
     *Auther:langxi
     *
     * 显示用户账户目前存在的收益金额
     * @param $member_id
     * @return array|Asset|mixed|null
     * @throws ErrorException
     */
    public static function showProfit($member_id)
    {
        //用户状态检测
        self::checkMember($member_id);
        $card = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $card = $card['profit'];
        return $card;
    }

    /**
     *Auther:langxi
     *
     * 前台用户提现
     * @param $member_id
     * @param $money
     * @throws ErrorException
     */
    public static function withdraw($member_id, $money)
    {
        ini_set('max_execution_time',60);
        $member_id = (int)$member_id;


        //判断是否为公司员工，若是取新浪余额，使网站余额与新浪余额保持一致
        $is_company = Catmiddle::find()->where(['uid'=>$member_id])->andWhere(['or','cid=1','cid=2'])->asArray()->one();
        if($is_company){
            $sina_withdraw = sinapay::querySinaBalance($member_id);//获取用户新浪余额
            if ($sina_withdraw['errorNum']) {
                Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                throw new ErrorException('新浪查询余额' . $sina_withdraw['errorMsg'], 7002);
            }
            $info = Info::find()->select(['balance'])->where(['member_id'=>$member_id])->asArray()->one();
            $balance = $info['balance'];
            if($balance < $sina_withdraw){
                $info = Info::findOne($member_id);
                $info->balance = $sina_withdraw['data']['available_balance'];
                $info = $info->save();
                if(!$info){
                    Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                    throw new ErrorException('更新公司员工账户余额失败');
                }
            }
        }


        //判断用户是否可进行提现操作
        $is_go = Info::find()->select(['status'])->where(['member_id'=>$member_id])->asArray()->one();
        if($is_go['status'] > 0){
            throw new ErrorException('处理中，请稍后再试');
        }
        Info::updateAll(['status'=>Info::GO_FOUR],['member_id'=>$member_id]);//进行操作，状态变为提现处理中

        //检测用户状态
        $checkMember = self::checkMember($member_id);
        if($checkMember){
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException($checkMember);
        }
        //检测提现次数，提现金额
        $check_balance = self::check_balance($member_id, $money);
        if($check_balance){
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException($check_balance);
        }
        $blance = self::showBlance($member_id);//账户余额
        Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
        if($blance < $money){
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException('提现金额大于余额，请重新输入');
        }

        //银行卡号
        $asset = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $bank_card = $asset['bank_card'];

        //将信息传输给第三方,新浪用户账户余额查询接口
        $sina_withdraw = sinapay::withdraw($member_id, $money);
        if ($sina_withdraw['errorNum']) {
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException('新浪查询余额' . $sina_withdraw['errorMsg'], 7002);
        }


        //事物回滚
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            //再次检测余额是否满足提现要求
            $info = Info::find()->where(['member_id' => $member_id])->asArray()->one();
            if ($info['balance'] < $money) {
                throw new ErrorException('可提现金额不足');
            }

            $asset = Info::findOne($member_id);
            $asset->balance = $asset['balance'] - $money;
            $asset->freeze = $asset['freeze'] + $money;
            $asset = $asset->save();
            if (!$asset) {
                throw new ErrorException('提现失败', 4003);
            }



            //消除冻结金额
            $asset = Info::findOne($member_id);
            $asset->freeze = $asset['freeze'] - $money;
            $asset->save();

            //提现成功进行提现记录
            $assetlog = new Log();
            $assetlog->member_id = $member_id;
            $assetlog->step = $money;
            $assetlog->action = 'Withdrawals/withdraw';
            $assetlog->status = self::WSUCCEED;//4为提现成功
            $assetlog->bankcard = $bank_card;
            $assetlog->remark = '提现成功';
            $assetlog->save();

            //.网站账户提现余额：$sina_withdraw['data']['money_site'];新浪赎回金额：$sina_withdraw['data']['money_sina']，
            //新浪用户标识：$sina_withdraw['data']['identity_id'] 中间用于信息获取的订单号$sina_withdraw['data']['out_trade_no']

            //T+0操作

            $immediate_withdraw = sinapay::immediate_withdraw($member_id,$money);
            if ($immediate_withdraw['errorNum']) {
                throw new ErrorException('新浪提现失败:' . $immediate_withdraw['errorMsg'], 7003);
            }
            Log::updateAll(['trade_no' => $immediate_withdraw['data']['trade_no']],['id' => $assetlog['id']]);

            //测试
//            $immediate_array = array('44','72','74','73','79','77');
//            if(in_array($member_id,$immediate_array)){
//                $immediate_withdraw = sinapay::immediate_withdraw($member_id,$money);
//                if ($immediate_withdraw['errorNum']) {
//                    throw new ErrorException('新浪提现失败:' . $immediate_withdraw['errorMsg'], 7003);
//                }
//                Log::updateAll(['trade_no' => $immediate_withdraw['data']['trade_no']],['id' => $assetlog['id']]);
//            }else{
//                // 不是T+0操作
//                //调用新浪提现接口，进行用户账户余额提现到银行卡
//                $sina_sinawithdraw = sinapay::sianWithdrawOnly($member_id,$money);
//                if ($sina_sinawithdraw['errorNum']) {
//                    throw new ErrorException('新浪提现失败:' . $sina_sinawithdraw['errorMsg'], 7003);
//                }
//                Log::updateAll(['trade_no' => $sina_sinawithdraw['data']['trade_no']],['id' => $assetlog['id']]);
//            }



            $transaction->commit();
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            //对提现失败信息进行记录
            $remark = $e->getMessage();
            $assetlog = new Log();
            $assetlog->member_id = $member_id;
            $assetlog->step = $money;
            $assetlog->action = 'Withdrawals/withdraw';
            $assetlog->status = self::WERROR;//-4为提现失败
            $assetlog->bankcard = $bank_card;
            $assetlog->remark = $remark;
            $assetlog->save();
            Log::updateAll(['trade_no' => $sina_sinawithdraw['data']['trade_no']],['id' => $assetlog['id']]);
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException($remark, 5005);

        }


    }

    /**
     *Auther:langxi
     *
     * 获取最大可赎回金额
     */
    public static function Redeem_max($member_id)
    {
        $Redeem_max = Order::find()->where(['member_id' => $member_id, 'status' => Order::STATUS_ACTIVE])->sum('money');
        if (!$Redeem_max) {
            $Redeem_max = 0;
        }
        return $Redeem_max;
    }

    /**
     * Authre:langxi
     *
     * 用户赎回操作
     */
    public static function User_redeem($member_id, $money)
    {
        ini_set('max_execution_time',60);
        //判断用户是否可进行赎回操作
        $is_go = Info::find()->select(['status'])->where(['member_id'=>$member_id])->asArray()->one();
        if($is_go['status'] > 0){
            throw new ErrorException('处理中，请稍后再试');
        }
        Info::updateAll(['status'=>Info::GO_THREE],['member_id'=>$member_id]);//赎回进行中
        //检查用户一日赎回
        $check_redeem = self::check_redeem($member_id,$money);
        if($check_redeem){
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            throw new ErrorException($check_redeem);
        }
        //判断赎回金额是否小于新浪账户与网站账户余额的差值，若小于则网站端进行赎回操作，先赎回利息，然后赎回订单中的金额。
        //若大于求取差值获取到网站需要赎回的金额和新浪部分需要赎回的金额，然后两端分别进行赎回操作，先赎回利息，然后赎回订单中的金额

        $asset = Info::find()->where(['member_id' => $member_id])->asArray()->one();
        $bank_card = $asset['bank_card'];

        //判断用户赎回金额是否可赎回。
        $order_money = (new \yii\db\Query())
            ->select(['money'])
            ->from('fund_orders')
            ->where(['member_id'=>$member_id])
            ->andWhere(['>', 'money', '0'])
            ->sum('money');
        if($order_money + $asset['profit'] < $money){
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);//赎回结束，操作状态变回常态
            throw new ErrorException('赎回金额大于在投金额，请重新输入');
        }

        $w_blance = self::showBlance($member_id);//网站余额
        $s_blance = sinapay::querySinaBalance($member_id);//调用第三方接口查看新浪账户可用余额（含货币基金
        if ($s_blance['errorNum']) {
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);//赎回结束，操作状态变回常态
            throw new ErrorException($s_blance['errorMsg'], 7003);
        }

        if ($w_blance > $s_blance['data']['available_balance']) {
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);//赎回结束，操作状态变回常态
            throw new ErrorException('账户异常请联系客服', 7003);
        }

        $m_fund = $s_blance['data']['available_balance'] - $w_blance;//新浪账户比网站账户多的钱
        if ($m_fund > $money) {
            //事物回滚
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //赎回金额小于新浪账户与网站账户的差值，仅网站端进行赎回操作
                $profit = (new \yii\db\Query())
                    ->select(['profit'])
                    ->from('asset_info')
                    ->where(['member_id' => $member_id])
                    ->one();
                $profit = $profit['profit'];//用户的可用收益
                if ($money <= $profit) {
                    //赎回金额小于等于用户可用收益
                    $info = Info::findOne($member_id);
                    $info->profit = $info['profit'] - $money;
                    $info = $info->save();
                    if (!$info) {
                        throw new ErrorException('赎回失败', 6001);
                    }

                    $info = Info::findOne(['member_id' => $member_id]);
                    $info->balance = $info->balance + $money;
                    $info = $info->save();
                    if (!$info) {
                        throw new ErrorException('赎回金额放入余额失败', 4002);
                    }


                    //由网站设定好的支付利息的账户进行利息支付
                    $sina_config = SinaConfig::find()->select(['sinapay_give_accrual'])->asArray()->one();
                    $profit_id = $sina_config['sinapay_give_accrual'];
                    $profit_info = Info::findOne($profit_id);
                    $profit_info->balance = $profit_info['balance'] - $money;
                    $profit_info = $profit_info->save();
                    if(!$profit_info){
                        throw new ErrorException('支付利息账户资金减少失败');
                    }
                    //写入职员账户记录表中
                    $clerk = new ClerkLog();
                    $clerk->member_id = $member_id;
                    $clerk->clerk_id = $profit_id;
                    $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
                    $clerk->step = $money;
                    $clerk->remark = '支付利息职员';
                    $clerk = $clerk->save();
                    if(!$clerk){
                        throw new ErrorException('写入职员账户记录失败');
                    }


                    //赎回成功进行记录
                    $assetlog = new Log();
                    $assetlog->member_id = $member_id;
                    $assetlog->step = $money;
                    $assetlog->action = 'Withdrawals/Redeem';
                    $assetlog->status = self::RSUCCEED;//赎回成功
                    $assetlog->bankcard = $bank_card;
                    $assetlog->remark = '赎回成功';
                    $assetlog->save();

                } else {
                    //赎回金额大于用户可用收益
                    $info = Info::findOne($member_id);
                    $info->profit = 0;
                    $info = $info->save();
                    if (!$info) {
                        throw new ErrorException('赎回收益失败', 6001);
                    }


                    //由网站设定好的支付利息的账户进行利息支付
                    $sina_config = SinaConfig::find()->select(['sinapay_give_accrual'])->asArray()->one();
                    $profit_id = $sina_config['sinapay_give_accrual'];
                    $profit_info = Info::findOne($profit_id);
                    $profit_info->balance = $profit_info['balance'] - $profit;
                    $profit_info = $profit_info->save();
                    if(!$profit_info){
                        throw new ErrorException('支付利息账户资金减少失败');
                    }
                    //写入职员账户记录表中
                    $clerk = new ClerkLog();
                    $clerk->member_id = $member_id;
                    $clerk->clerk_id = $profit_id;
                    $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
                    $clerk->step = $profit;
                    $clerk->remark = '支付利息职员';
                    $clerk = $clerk->save();
                    if(!$clerk){
                        throw new ErrorException('写入职员账户记录失败');
                    }


                    $redeem_money = $money - $profit;
                    $Redeem = self::Redeem($member_id, $redeem_money);
                    if (!$Redeem) {
                        throw new ErrorException('生成赎回字典失败', 4002);
                    } else {
                        $Redeem = self::Set_redeem($Redeem);
                        if (!$Redeem) {
                            throw new ErrorException('赎回失败');
                        } else {


                            $info = Info::findOne(['member_id' => $member_id]);
                            $info->balance = $info->balance + $money;
                            $info->invest = $info['invest'] - $money;
                            $info = $info->save();
                            if (!$info) {
                                throw new ErrorException('赎回金额放入余额失败', 4002);
                            }

                            //赎回成功进行提现记录
                            $assetlog = new Log();
                            $assetlog->member_id = $member_id;
                            $assetlog->step = $money;
                            $assetlog->action = 'Withdrawals/Redeem';
                            $assetlog->status = self::RSUCCEED;//赎回成功
                            $assetlog->bankcard = $bank_card;
                            $assetlog->remark = '赎回成功';
                            $assetlog->save();

                        }
                    }
                }
                $transaction->commit();
                Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);//赎回结束，操作状态变回常态
                return true;

            } catch (\Exception $e) {
                $transaction->rollBack();
                //对提现失败信息进行记录
                $remark = $e->getMessage();
                $assetlog = new Log();
                $assetlog->member_id = $member_id;
                $assetlog->step = $money;
                $assetlog->action = 'Withdrawals/Redeem';
                $assetlog->status = self::RERROR;//赎回失败
                $assetlog->bankcard = $bank_card;
                $assetlog->remark = '赎回失败' . $remark;
                $assetlog->save();
                Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                return false;
            }
        } else {
            //赎回金额大于新浪账户与网站账户的差值，取得新浪端赎回金额，网站与新浪都进行赎回操作

            $s_money = $money - $m_fund;//新浪端赎回金额

            $profit = (new \yii\db\Query())
                ->select(['profit'])
                ->from('asset_info')
                ->where(['member_id' => $member_id])
                ->one();
            $profit = $profit['profit'];//用户的可用收益
            if ($s_money <= $profit) {
                //事物回滚  赎回收益
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    //新浪赎回金额小于等于用户可用收益
                    $info = Info::findOne($member_id);
                    $info->profit = $info['profit'] - $money;
                    $info = $info->save();
                    if (!$info) {
                        throw new ErrorException('赎回失败', 6001);
                    }


                    //由网站设定好的支付利息的账户进行利息支付
                    $sina_config = SinaConfig::find()->select(['sinapay_give_accrual'])->asArray()->one();
                    $profit_id = $sina_config['sinapay_give_accrual'];
                    $profit_info = Info::findOne($profit_id);
                    $profit_info->balance = $profit_info['balance'] - $money;
                    $profit_info = $profit_info->save();
                    if(!$profit_info){
                        throw new ErrorException('支付利息账户资金减少失败');
                    }
                    //写入职员账户记录表中
                    $clerk = new ClerkLog();
                    $clerk->member_id = $member_id;
                    $clerk->clerk_id = $profit_id;
                    $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
                    $clerk->step = $money;
                    $clerk->remark = '支付利息职员';
                    $clerk = $clerk->save();
                    if(!$clerk){
                        throw new ErrorException('写入职员账户记录失败');
                    }


                    //调用第三方新浪接口赎回收益
                    $sina_ransom = sinapay::giveInterest($member_id, $s_money);//赎回收益
                    if ($sina_ransom['errorNum']) {
                        throw new ErrorException( $sina_ransom['errorMsg'], 7002);
                    }

                    $info = Info::findOne(['member_id' => $member_id]);
                    $info->balance = $info->balance + $money;
                    $info = $info->save();
                    if (!$info) {
                        throw new ErrorException('赎回金额放入余额失败', 4002);
                    }

                    //赎回成功进行提现记录
                    $assetlog = new Log();
                    $assetlog->member_id = $member_id;
                    $assetlog->step = $money;
                    $assetlog->action = 'Withdrawals/Redeem';
                    $assetlog->status = self::RSUCCEED;//赎回成功
                    $assetlog->bankcard = $bank_card;
                    $assetlog->remark = '赎回成功';
                    $assetlog->trade_no = $sina_ransom['data']['trade_no'];
                    $assetlog->save();

                    $transaction->commit();
                    Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);//赎回结束，操作状态变回常态
                    return true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    //对提现失败信息进行记录
                    $remark = $e->getMessage();
                    $assetlog = new Log();
                    $assetlog->member_id = $member_id;
                    $assetlog->step = $money;
                    $assetlog->action = 'Withdrawals/Redeem';
                    $assetlog->status = self::RERROR;//赎回失败
                    $assetlog->bankcard = $bank_card;
                    $assetlog->remark = '赎回失败' . $remark;
                    $assetlog->trade_no = $sina_ransom['data']['trade_no'];
                    $assetlog->save();
                    Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                    return false;
                }
            } else {
                //新浪赎回金额大于用户可用收益
                $rh_money = 0;//赎回金额
                if($profit > 0) {
                    //收益大于0，赎回收益
                    //事物回滚 赎回收益
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $info = Info::findOne($member_id);
                        $info->profit = 0;
                        $info = $info->save();
                        if (!$info) {
                            throw new ErrorException('赎回收益失败', 6001);
                        }


                        //由网站设定好的支付利息的账户进行利息支付
                        $sina_config = SinaConfig::find()->select(['sinapay_give_accrual'])->asArray()->one();
                        $profit_id = $sina_config['sinapay_give_accrual'];
                        $profit_info = Info::findOne($profit_id);
                        $profit_info->balance = $profit_info['balance'] - $profit;
                        $profit_info = $profit_info->save();
                        if(!$profit_info){
                            throw new ErrorException('支付利息账户资金减少失败');
                        }
                        //写入职员账户记录表中
                        $clerk = new ClerkLog();
                        $clerk->member_id = $member_id;
                        $clerk->clerk_id = $profit_id;
                        $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
                        $clerk->step = $profit;
                        $clerk->remark = '支付利息职员';
                        $clerk = $clerk->save();
                        if(!$clerk){
                            throw new ErrorException('写入职员账户记录失败');
                        }



                        $info = Info::findOne(['member_id' => $member_id]);
                        $info->balance = $info->balance + $profit;
                        $info = $info->save();
                        if (!$info) {
                            throw new ErrorException('赎回收益放入余额失败', 4002);
                        }

                        $sina_ransom = sinapay::giveInterest($member_id, (string)$profit);//调用第三方接口赎回收益
                        if ($sina_ransom['errorNum']) {
                            throw new ErrorException( $sina_ransom['errorMsg'], 7002);
                        }


                        $rh_money = $profit;//赎回金额
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        //对提现失败信息进行记录
                        $remark = $e->getMessage();
                        $assetlog = new Log();
                        $assetlog->member_id = $member_id;
                        $assetlog->step = $money;
                        $assetlog->action = 'Withdrawals/Redeem';
                        $assetlog->status = self::RERROR;//赎回失败
                        $assetlog->bankcard = $bank_card;
                        $assetlog->remark = '赎回失败' . $remark;
                        $assetlog->save();
                        Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                        return false;
                    }
                }
                $money = $money - $profit;
                $redeem_dic = self::Redeem($member_id, $money);//获取债权字典

                foreach ($redeem_dic as $k => $v) {
                    //事物回滚
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        //网站赎回部分
                        //order表金额赎回
                        $order = Order::findOne($v['order_id']);
                        $order->money = $order['money'] - $v['thirdmoney'];
                        if ($order['money'] == '0') {
                            $order->status = Order::STATUS_DELETE;
                        }
                        $order = $order->save();
                        if (!$order) {
                            throw new ErrorException('赎回转让债权失败');
                        }

                        //thirdorder表金额赎回
                        $thirdorder = Thirdorder::findOne($v['thirdorder_id']);
                        $thirdorder->money = $thirdorder['money'] - $v['thirdmoney'];
                        if ($thirdorder['money'] == '0') {
                            $thirdorder->status = Thirdorder::STATUS_DELETED;
                        }
                        $thirdorder->mcmoney = $thirdorder['mcmoney'] + $v['thirdmoney'];//用户赎回，最大债权人支付此金额，记录
                        $thirdorder->ocmoney = $thirdorder['ocmoney'] - $v['thirdmoney'];//用户赎回，原始债权人金额减少，记录
                        $thirdorder = $thirdorder->save();
                        if (!$thirdorder) {
                            throw new ErrorException('赎回第三方转让债权失败');
                        }

                        //thirdproduct第三方债权已投金额减少
                        $thirdproduct = Thirdproduct::findOne($v['thirdproduct_id']);
                        $thirdproduct->invest_sum = $thirdproduct['invest_sum'] - $v['thirdmoney'];
                        $thirdproduct->mcmoney = $thirdproduct['mcmoney'] + $v['thirdmoney'];//赎回导致最大债权人金额增加
                        $thirdproduct = $thirdproduct->save();
                        if (!$thirdproduct) {
                            throw new ErrorException('赎回第三方债权已投金额失败');
                        }



                        //获取债权的最大债权人id，将用户赎回的钱从最大债权人的账户中减去
                        $maxcreditor = Thirdproduct::find()->select(['maxcreditor'])->where(['id'=>$v['thirdproduct_id']])->asArray()->one();
                        $maxcreditor = $maxcreditor['maxcreditor'];
                        $is_max = Catmiddle::find()->where(['cid'=>'1','uid'=>$maxcreditor])->asArray()->one();
                        if(!$is_max){
                            throw new ErrorException('最大债权人异常');
                        }
                        $max_info = Info::findOne($maxcreditor);
                        $max_info->balance = $max_info['balance'] - $v['thirdmoney'];
                        $max_info = $max_info->save();
                        if(!$max_info){
                            throw new ErrorException('消减最大债权人账户金额失败');
                        }
                        //写入职员账户记录表中
                        $clerk = new ClerkLog();
                        $clerk->member_id = $member_id;
                        $clerk->clerk_id = $maxcreditor;
                        $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
                        $clerk->step = $v['thirdmoney'];
                        $clerk->remark = '最大债权人';
                        $clerk = $clerk->save();
                        if(!$clerk){
                            throw new ErrorException('写入职员账户记录失败');
                        }




                        //新浪赎回部分

                        $k_money = $s_money - $v['thirdmoney'];//赎回金额

                        if ($k_money > 0) {
                            $s_money = $k_money;
                            //获取最大债权人
                            $maxcreditor = (new \yii\db\Query())
                                ->select(['maxcreditor'])
                                ->from('fund_thirdproduct')
                                ->where(['id'=>$v['thirdproduct_id']])
                                ->one();
                            $maxcreditor = $maxcreditor['maxcreditor'];
                            //调用第三方新浪接口赎回
                            $sina_ransom = sinapay::sinaRansom($member_id, (string)$v['thirdmoney'], $maxcreditor);
                            if ($sina_ransom['errorNum']) {
                                throw new ErrorException($sina_ransom['errorMsg'], 7002);
                            }

                            $info = Info::findOne(['member_id' => $member_id]);
                            $info->balance = $info->balance + $v['thirdmoney'];
                            $info->invest = $info['invest'] - $v['thirdmoney'];
                            $info = $info->save();
                            if (!$info) {
                                $money_log = $money - $s_money + $v['thirdmoney'];
                                throw new ErrorException('成功赎回' . $money_log . '元', 4002);
                            }


                            $rh_money = $rh_money + $v['thirdmoney'];//赎回金额
                        } else {
                            //获取最大债权人
                            $maxcreditor = (new \yii\db\Query())
                                ->select(['maxcreditor'])
                                ->from('fund_thirdproduct')
                                ->where(['id'=>$v['thirdproduct_id']])
                                ->one();
                            $maxcreditor = $maxcreditor['maxcreditor'];
                            //调用第三方新浪接口赎回
                            $sina_ransom = sinapay::sinaRansom($member_id, (string)$s_money, $maxcreditor);//暂定38为支付利息的用户id
                            if ($sina_ransom['errorNum']) {
                                throw new ErrorException($sina_ransom['errorMsg'], 7002);
                            }

                            $info = Info::findOne(['member_id' => $member_id]);
                            $info->balance = $info->balance + $s_money + $m_fund;
                            $info->invest = $info['invest'] - $s_money - $m_fund;
                            $info = $info->save();
                            if (!$info) {
                                $money_log = $money - $s_money + $m_fund;
                                throw new ErrorException('赎回' . $money_log . '元', 4002);
                            }


                            $rh_money = $rh_money + $s_money + $m_fund;//赎回金额

                        }

                        $transaction->commit();

                    } catch (\Exception $e) {
                        $transaction->rollBack();

                        //对提现失败信息进行记录
                        $remark = $e->getMessage();
                        $assetlog = new Log();
                        $assetlog->member_id = $member_id;
                        $assetlog->step = $s_money;
                        $assetlog->action = 'Withdrawals/Redeem';
                        $assetlog->status = self::RERROR;//赎回失败
                        $assetlog->bankcard = $bank_card;
                        $assetlog->remark = '赎回失败' . $remark;
                        $assetlog->trade_no = $sina_ransom['data']['trade_no'];
                        $assetlog->save();
                        Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
                        throw new ErrorException($remark, 5005);
                        break;//异常跳出赎回操作

                    }

                }
                $assetlog = new Log();
                $assetlog->member_id = $member_id;
                $assetlog->step = $rh_money;
                $assetlog->action = 'Withdrawals/Redeem';
                $assetlog->status = self::RSUCCEED;//赎回成功
                $assetlog->bankcard = $bank_card;
                $assetlog->remark = '赎回' . $rh_money . '元';
                $assetlog->trade_no = $sina_ransom['data']['trade_no'];
                $assetlog->save();



            }
            Info::updateAll(['status'=>'0'],['member_id'=>$member_id]);
            return true;

        }

    }


    /**
     * Auther:langxi
     * $money:需要赎回的订单的金额
     * 生成赎回债权字典
     * @param $member_id
     * @param $money
     * @return mixed
     */
    private static function Redeem($member_id, $money)
    {
        //获取一对一用户生效订单
        $order = Order::find()->where(['type' => 0, 'member_id' => $member_id, 'status' => Order::STATUS_ACTIVE])->orderBy('end_at asc')->asArray()->all();
        //将赎回的一对一债权订单数据写入数组中
        $Zhi = array();
        foreach ($order as $key => $val) {
            $thirdorder = thirdorder::find()->where(['member_id' => $member_id, 'order_id' => $val['id'], 'status' => thirdorder::STATUS_ACTIVE])->asArray()->all();

            foreach ($thirdorder as $k => $v) {
                $Zhi[$key . 'Z' . $k]['order_id'] = $val['id'];
                $Zhi[$key . 'Z' . $k]['thirdorder_id'] = $v['id'];
                $Zhi[$key . 'Z' . $k]['thirdproduct_id'] = $v['thirdproduct_id'];
                $Zhi[$key . 'Z' . $k]['thirdmoney'] = $v['money'];
                $Zhi[$key . 'Z' . $k]['end_at'] = $v['end_at'];
            }

        }

        //对数组进行排序处理，让其按照金额大小从小到大为主，结束时间从早到晚为辅进行排序
        $thirdmoney = array();
        $end_at = array();
        foreach ($Zhi as $k => $v) {
            $thirdmoney[$k] = $v['thirdmoney'];
            $end_at[$k] = $v['end_at'];
        }
        $thirdmoneyone = array_unique($thirdmoney);
        $end_atone = array_unique($end_at);
        if(count($end_atone) == '1' && count($thirdmoneyone) !='1'){
            array_multisort($thirdmoney, SORT_ASC, $Zhi);
        }elseif(count($end_atone) != '1' && count($thirdmoneyone) =='1'){
            array_multisort($end_at, SORT_ASC, $Zhi);
        }elseif(count($end_atone) != '1' && count($thirdmoneyone) !='1'){
            array_multisort($thirdmoney, SORT_ASC, $end_at, SORT_ASC, $Zhi);
        }


        //生成一对多订单的赎回数据字典
        foreach ($Zhi as $k => $v) {
            $money = ($money*100 - $v['thirdmoney']*100)/100;
            if ($money > 0) {
                $Redeem[$k . 'R']['order_id'] = $v['order_id'];
                $Redeem[$k . 'R']['thirdorder_id'] = $v['thirdorder_id'];
                $Redeem[$k . 'R']['thirdproduct_id'] = $v['thirdproduct_id'];
                $Redeem[$k . 'R']['thirdmoney'] = $v['thirdmoney'];
                $Redeem[$k . 'R']['end_at'] = $v['end_at'];
                $Redeem[$k . 'R']['type'] = 0;
            } else {
                $Redeem[$k . 'R']['order_id'] = $v['order_id'];
                $Redeem[$k . 'R']['thirdorder_id'] = $v['thirdorder_id'];
                $Redeem[$k . 'R']['thirdproduct_id'] = $v['thirdproduct_id'];
                $Redeem[$k . 'R']['thirdmoney'] = $money + $v['thirdmoney'];
                $Redeem[$k . 'R']['end_at'] = $v['end_at'];
                $Redeem[$k . 'R']['type'] = 0;
                return $Redeem;
            }

        }


        //若赎回一对一生效订单之后依然不满足，赎回金额，则对一对多金额进行赎回
        $order = Order::find()->where(['type' => 1, 'member_id' => $member_id, 'status' => Order::STATUS_ACTIVE])->asArray()->all();
        //获取到全部的一对多订单数据，对order和thirdorder进行处理放入到数组中
        $Zuan = array();
        foreach ($order as $key => $val) {

            $thirdorder = thirdorder::find()->where(['member_id' => $member_id, 'order_id' => $val['id'], 'status' => thirdorder::STATUS_ACTIVE])->asArray()->all();

            foreach ($thirdorder as $k => $v) {
                $Zuan[$key . 'R' . $k]['order_id'] = $val['id'];
                $Zuan[$key . 'R' . $k]['thirdorder_id'] = $v['id'];
                $Zuan[$key . 'R' . $k]['thirdproduct_id'] = $v['thirdproduct_id'];
                $Zuan[$key . 'R' . $k]['thirdmoney'] = $v['money'];
                $Zuan[$key . 'R' . $k]['end_at'] = $v['end_at'];
            }


        }

        //对数组进行排序处理，让其按照金额大小从小到大为主，结束时间从早到晚为辅进行排序
        $thirdmoney = array();
        $end_at = array();
        foreach ($Zuan as $k => $v) {
            $thirdmoney[$k] = $v['thirdmoney'];
            $end_at[$k] = $v['end_at'];
        }
        $thirdmoneyone = array_unique($thirdmoney);
        $end_atone = array_unique($end_at);
        if(count($end_atone) == '1' && count($thirdmoneyone) !='1'){
            array_multisort($thirdmoney, SORT_ASC, $Zuan);
        }elseif(count($end_atone) != '1' && count($thirdmoneyone) =='1'){
            array_multisort($end_at, SORT_ASC, $Zuan);
        }elseif(count($end_atone) != '1' && count($thirdmoneyone) !='1'){
            array_multisort($thirdmoney, SORT_ASC, $end_at, SORT_ASC, $Zuan);
        }

        //生成一对多订单的赎回数据字典
        foreach ($Zuan as $k => $v) {
            $money = ($money*100 - $v['thirdmoney']*100)/100;//避免误差
            if ($money > 0) {
                $Redeem[$k . 'R']['order_id'] = $v['order_id'];
                $Redeem[$k . 'R']['thirdorder_id'] = $v['thirdorder_id'];
                $Redeem[$k . 'R']['thirdproduct_id'] = $v['thirdproduct_id'];
                $Redeem[$k . 'R']['thirdmoney'] = $v['thirdmoney'];
                $Redeem[$k . 'R']['end_at'] = $v['end_at'];
                $Redeem[$k . 'R']['type'] = 1;
            } else {
                $Redeem[$k . 'R']['order_id'] = $v['order_id'];
                $Redeem[$k . 'R']['thirdorder_id'] = $v['thirdorder_id'];
                $Redeem[$k . 'R']['thirdproduct_id'] = $v['thirdproduct_id'];
                $Redeem[$k . 'R']['thirdmoney'] = $money + $v['thirdmoney'];
                $Redeem[$k . 'R']['end_at'] = $v['end_at'];
                $Redeem[$k . 'R']['type'] = 1;
                return $Redeem;
            }

        }

    }

    /**
     * Auther:langxi
     * 根据赎回债权字典进行赎回操作
     * @param $Redeem
     */
    private static function Set_redeem($Redeem)
    {
        foreach ($Redeem as $k => $v) {

            //order表金额赎回
            $order = Order::findOne($v['order_id']);
            $order->money = $order['money'] - $v['thirdmoney'];
            if ($order['money'] == '0') {
                $order->status = Order::STATUS_DELETE;
            }
            $order = $order->save();
            if (!$order) {
                throw new ErrorException('赎回转让债权失败');
            }

            //thirdorder表金额赎回
            $thirdorder = Thirdorder::findOne($v['thirdorder_id']);
            $thirdorder->money = $thirdorder['money'] - $v['thirdmoney'];
            if ($thirdorder['money'] == '0') {
                $thirdorder->status = Thirdorder::STATUS_DELETED;
            }
            $thirdorder->mcmoney = $thirdorder['mcmoney'] + $v['thirdmoney'];//记录最大债权增加
            $thirdorder->ocmoney = $thirdorder['ocmoney'] - $v['thirdmoney'];//记录原始债权金额减少
            $thirdorder = $thirdorder->save();
            if (!$thirdorder) {
                throw new ErrorException('赎回第三方转让债权失败');
            }

            //thirdproduct第三方债权已投金额减少
            $thirdproduct = Thirdproduct::findOne($v['thirdproduct_id']);
            $thirdproduct->invest_sum = $thirdproduct['invest_sum'] - $v['thirdmoney'];
            $thirdproduct->mcmoney = $thirdproduct['mcmoney'] + $v['thirdmoney'];//赎回导致最大债权人金额增加
            $thirdproduct = $thirdproduct->save();
            if (!$thirdproduct) {
                throw new ErrorException('赎回第三方债权已投金额失败');
            }


            //获取债权的最大债权人id，将用户赎回的钱从最大债权人的账户中减去
            $maxcreditor = Thirdproduct::find()->select(['maxcreditor'])->where(['id'=>$v['thirdproduct_id']])->asArray()->one();
            $maxcreditor = $maxcreditor['maxcreditor'];
            $is_max = Catmiddle::find()->where(['cid'=>'1','uid'=>$maxcreditor])->asArray()->one();
            if(!$is_max){
                throw new ErrorException('最大债权人异常');
            }
            $max_info = Info::findOne($maxcreditor);
            $max_info->balance = $max_info['balance'] - $v['thirdmoney'];
            $max_info = $max_info->save();
            if(!$max_info){
                throw new ErrorException('消减最大债权人账户金额失败');
            }

            //写入职员账户记录表中
            $member_id = Order::find()->select(['member_id'])->where(['id'=>$v['order_id']])->asArray()->one();
            $member_id = $member_id['member_id'];
            $clerk = new ClerkLog();
            $clerk->member_id = $member_id;
            $clerk->clerk_id = $maxcreditor;
            $clerk->behav = ClerkLog::CLERK_BEHAV_TWO;
            $clerk->step = $v['thirdmoney'];
            $clerk->remark = '最大债权人';
            $clerk = $clerk->save();
            if(!$clerk){
                throw new ErrorException('写入职员账户记录失败');
            }


        }
        return true;

    }




    /**
     *Auther:langxi
     *
     * 检测一日提现次数，提现金额
     * @param $member_id
     * @param $money
     * @return bool
     * @throws ErrorException
     */
    private static function check_balance($member_id, $money)
    {
        //检测用户提现次数
        $bankTimes = self::bankTimes($member_id);
        $times = self::getBankTime();
        if ($bankTimes >= $times) {
            $result = '用户提现次数达到限制';
            return $result;
        }
        //检测用户提现金额
       // $lastTotal = self::cashTotal($member_id);
       // $cashTotal = $lastTotal + $money;
        $cash = self::getcash();
        if ($money > $cash) {
            $result = '对不起，您的提现金额超过每次允许的最大限额';
            return $result;
        }
        //若账户余额大于最小提现额度，则判断提现金额是否大于最小提现金额
        $balance = Info::find()->select(['balance'])->where(['member_id'=>$member_id])->asArray()->one();
        $mincash = self::getminmoney();
        if($balance > $mincash){
            if($mincash > $money){
                $result = '对不起，您的提现金额小于最小提现额度'.$mincash;
                return $result;
            }
        }

        return false;

    }

    /**
     *Auther:langxi
     *status表示行为状态,step:金额
     * 用户一日内提现总金额
     * @param $member_id
     * @return mixed
     */
    private static function cashTotal($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_WITHDRAW_SUC, 'action' => 'Withdrawals/withdraw'])
            ->andWhere(['>=', 'create_at', $min_time])
            ->andWhere(['<', 'create_at', $max_time])
            ->sum('step');
    }

    /**
     *Auther:langxi
     *
     * 用户一日内提现次数
     * @param $member_id
     * @return int|string
     */
    private static function bankTimes($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_WITHDRAW_SUC, 'action' => 'Withdrawals/withdraw'])
            ->andWhere(['>=', 'create_at', $min_time])
            ->andWhere(['<', 'create_at', $max_time])
            ->count('member_id');

    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日提现最大金额
     * @return string
     */
    private static function getcash()
    {
        $result = AssetConfig::find()->select(['withdraw_max'])->asArray()->one();
        if ($result) {
            $result = $result['withdraw_max'];
        } else {
            throw new ErrorException('获取允许用户每日的最大提现金额失败', 1004);
        }
        return $result;
    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日提现最小金额
     * @return string
     */
    private static function getminmoney()
    {
        $result = AssetConfig::find()->select(['withdraw_min'])->asArray()->one();
        if ($result) {
            $result = $result['withdraw_min'];
        } else {
            throw new ErrorException('获取允许用户每日的最小提现金额失败', 1004);
        }
        return $result;
    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日允许的最大提现次数
     * @return string
     */
    private static function getBankTime()
    {
        $result = AssetConfig::find()->select(['withdraw_num'])->asArray()->one();
        if ($result) {
            $result = $result['withdraw_num'];
        } else {
            throw new ErrorException('获取允许用户每日的最大提现次数失败', 1004);
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
    private static function check_redeem($member_id, $money)
    {
        //检测用户赎回次数
        $bankTimes = self::redeemTimes($member_id);
        $times = self::redeemkTime();
        if ($bankTimes >= $times) {
            $result = '用户赎回次数达到限制';
            return $result;
        }
        //检测用户赎回金额
        //$lastTotal = self::redeemTotal($member_id);
        //$cashTotal = $lastTotal + $money;
        $cash = self::redeemcash();
        if ($money > $cash) {
            $result = '用户的赎回金额超过了每次赎回允许的最大限额';
            return $result;
        }
        //若账户余额大于最小赎回额度，则判断赎回金额是否大于最小赎回金额
        $balance = Info::find()->select(['invest'])->where(['member_id'=>$member_id])->asArray()->one();
        $mincash = self::redeemminmoney();
        if($balance > $mincash){
            if($mincash > $money){
                $result = '对不起，您的赎回金额小于最小赎回额度';
                return $result;
            }
        }
        return false;
    }



    /**
     *Auther:langxi
     *status表示行为状态,step:金额
     * 用户一日内赎回总金额
     * @param $member_id
     * @return mixed
     */
    private static function redeemTotal($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_REDEM_SUC, 'action' => 'Withdrawals/Redeem'])
            ->andWhere(['>=', 'create_at', $min_time])
            ->andWhere(['<', 'create_at', $max_time])
            ->sum('step');
    }

    /**
     *Auther:langxi
     *
     * 用户一日内赎回次数
     * @param $member_id
     * @return int|string
     */
    private static function redeemTimes($member_id)
    {
        $min_time = strtotime(date('Y-m-d'));
        $max_time = strtotime(date('Y-m-d', strtotime('+1 day')));
        return Log::find()
            ->andWhere(['member_id' => $member_id, 'status' => Log::STATUS_REDEM_SUC, 'action' => 'Withdrawals/Redeem'])
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
    private static function redeemcash()
    {
        $result = AssetConfig::find()->select(['ransom_max'])->asArray()->one();
        if ($result) {
            $result = $result['ransom_max'];
        } else {
            throw new ErrorException('获取允许用户每日的最大赎回金额失败', 1004);
        }
        return $result;
    }


    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日赎回最小金额
     * @return string
     */
    private static function redeemminmoney()
    {
        $result = AssetConfig::find()->select(['ransom_min'])->asArray()->one();
        if ($result) {
            $result = $result['ransom_min'];
        } else {
            throw new ErrorException('获取允许用户每日的最小赎回金额失败', 1004);
        }
        return $result;
    }

    /**
     *Auther:langxi
     *
     * 获取系统规定的用户每日允许的最大赎回次数
     * @return string
     */
    private static function redeemkTime()
    {
        $result = AssetConfig::find()->select(['ransom_num'])->asArray()->one();
        if ($result) {
            $result = $result['ransom_num'];
        } else {
            throw new ErrorException('获取允许用户每日的最大赎回次数失败', 1004);
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
}