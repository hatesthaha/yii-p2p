<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/8/13
 * Time: 14:37
 */

namespace frontend\actions;


use common\models\base\asset\Info;
use common\models\base\asset\Log;
use common\models\sinapay\SinaConfig;
use common\models\sinapay\SinaDeposit;
use common\models\sinapay\SinaNotify;
use common\models\sinapay\SinaNotifyBatchPay2bank;
use common\models\sinapay\SinaNotifyBatchTrade;
use common\models\sinapay\SinaNotifyDeposit;
use common\models\sinapay\SinaNotifyRefund;
use common\models\sinapay\SinaNotifyTrade;
use common\models\sinapay\SinaNotifyWithdraw;
use framework\sinapay\Weibopay;

class sina
{
    private $sina_config = array();
    private $sina_config_two = array();

    public function __construct()
    {
        //用户配置--获取数据库内容
        $sina_config = $this->getConfig();
        $config = array(
            'sinapay_version' => trim($sina_config->sinapay_version),
            'sinapay_partner_id' => trim($sina_config->sinapay_partner_id),
            'sign_type' => trim($sina_config->sign_type),
            'sinapay_md5_key' => trim($sina_config->sinapay_md5_key),
            'sinapay_input_charset' => trim($sina_config->sinapay_input_charset),
            'sinapay_rsa_sign_private_key' => trim($sina_config->sinapay_rsa_sign_private_key),
            'sinapay_rsa_sign_public_key' => trim($sina_config->sinapay_rsa_sign_public_key),
            'sinapay_rsa_public__key' => trim($sina_config->sinapay_rsa_public__key),
            'sinapay_mgs_url' => trim($sina_config->sinapay_mgs_url),
            'sinapay_mas_url' => trim($sina_config->sinapay_mas_url)
        );
        $this->sina_config = $config;

    }

    /**
     * 获取配置参数
     * @return null|static
     */
    public function getConfig()
    {
        $config = SinaConfig::findOne(['id' => '1']);
        return $config;
    }

    /**
     * 创建激活会员
     * @param $identity_id 用户标识信息
     * @param string $member_type
     * @param string $extend_param
     * @return array|mixed
     */
    public function create_activate_member($identity_id, $member_type = '1', $extend_param = '')
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'member_type' => $member_type,
            'extend_param' => $extend_param
        );
        $respond = $this->send('create_activate_member', $post);
        return $respond;
    }

    /**
     * 设置实名信息
     * @param $identity_id 用户标识信息
     * @param $real_name 真实姓名
     * @param $cert_no 身份证号
     * @return array|mixed
     */
    public function set_real_name($identity_id, $real_name, $cert_no)
    {
        //用户信息加密处理
        $public_key = $this->sina_config['sinapay_rsa_public__key'];
        $weibopay = new Weibopay();
        $real_name = $weibopay->Rsa_encrypt($real_name, $public_key);
        $cert_no = $weibopay->Rsa_encrypt($cert_no, $public_key);
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'real_name' => $real_name,
            'cert_type' => 'IC',
            'cert_no' => $cert_no,
            'need_confirm' => 'Y'
        );
        $respond = $this->send('set_real_name', $post);
        return $respond;
    }

    /**
     * 绑定认证信息
     * @param $identity_id 用户标识信息
     * @param $verify_entity 认证信息（用户手机号）
     * @return array|mixed
     */
    public function binding_verify($identity_id, $verify_entity)
    {
        $public_key = $this->sina_config['sinapay_rsa_public__key'];
        $weibopay = new Weibopay();
        $verify_entity = $weibopay->Rsa_encrypt($verify_entity, $public_key);
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'verify_type' => 'MOBILE',
            'verify_entity' => $verify_entity
        );
        $respond = $this->send('binding_verify', $post);
        return $respond;
    }

    /**
     * 解绑认证信息
     * @param $identity_id 用户标识信息
     * @return array|mixed
     */
    public function unbinding_verify($identity_id)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'verify_type' => 'MOBILE'
        );
        $respond = $this->send('unbinding_verify', $post);
        return $respond;
    }

    /**
     * 查询认证信息
     * @param $identity_id
     * @return array|mixed
     */
    public function query_verify($identity_id)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'verify_type' => 'MOBILE',
            'is_mask' => 'Y'
        );
        $respond = $this->send('query_verify', $post);
        return $respond;
    }

    /**
     *绑定银行卡(姓名身份证---使用认证信息)
     * @param $request_no 绑定订单号
     * @param $identity_id 用户信息标志
     * @param $bank_account_no 银行卡号
     * @param $phone_no 银行预留手机号
     * @param $bank_code 银行编号
     * @param $card_type 银行卡类型 (借记卡，信用卡)
     * @param $card_attribute 银行卡属性 （对公，对私）
     * @param $province 开卡省份
     * @param $city 开卡城市
     * @param $bank_branch 支行名称
     * @return array|mixed
     */
    public function binding_bank_card($request_no, $identity_id, $bank_account_no, $phone_no, $bank_code, $card_type, $card_attribute, $province, $city, $bank_branch)
    {
        $public_key = $this->sina_config['sinapay_rsa_public__key'];
        $weibopay = new Weibopay();
        $bank_account_no = $weibopay->Rsa_encrypt($bank_account_no, $public_key);
        $phone_no = $weibopay->Rsa_encrypt($phone_no, $public_key);
        $post = array(
            'request_no' => $request_no,
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'bank_code' => $bank_code,
            'bank_account_no' => $bank_account_no,
            'card_type' => $card_type,
            'card_attribute' => $card_attribute,
            'cert_type' => 'IC',
            'phone_no' => $phone_no,
            'province' => $province,
            'city' => $city,
            'verify_mode' => 'SIGN',
            'bank_branch' => $bank_branch
        );
        $respond = $this->send('binding_bank_card', $post);
        return $respond;
    }

    /**
     * 绑定银行卡推进
     * @param $ticket 绑卡时返回的ticket
     * @param $valid_code 手机验证码
     * @return array|mixed
     */
    public function binding_bank_card_advance($ticket, $valid_code)
    {
        $post = array(
            'ticket' => $ticket,
            'valid_code' => $valid_code
        );
        $respond = $this->send('binding_bank_card_advance', $post);
        return $respond;
    }

    /**
     * 解绑银行卡
     * @param $identity_id
     * @param $card_id 钱包系统卡ID
     * @return array|mixed
     */
    public function unbinding_bank_card($identity_id, $card_id)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'card_id' => $card_id
        );
        $respond = $this->send('unbinding_bank_card', $post);
        return $respond;
    }

    /**
     * 查询银行卡
     * @param $identity_id
     * @param $card_id 钱包系统卡ID
     * @return array|mixed
     */
    public function query_bank_card($identity_id, $card_id)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'card_id' => $card_id
        );
        $respond = $this->send('query_bank_card', $post);
        return $respond;
    }

    /**
     * 查询余额/基金份额
     * @param $identity_id
     * @param string $identity_type 用户标识类型
     * @param string $account_type 账户类型
     * @return array|mixed
     */
    public function query_balance($identity_id, $identity_type = 'UID', $account_type = 'SAVING_POT')
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => $identity_type,
            'account_type' => $account_type
        );
        $respond = $this->send('query_balance', $post);
        return $respond;
    }

    /**
     * 查询收支明细
     * @param $identity_id
     * @param $account_type 账户类型
     * @param $start_time 开始时间
     * @param $end_time 结束时间
     * @param $extend_param 扩展参数
     * @param $page_no 页号，从1开始，默认为1
     * @param $page_size 每页记录数，不超过30，默认20
     * @return array|mixed
     */
    public function query_account_details($identity_id, $account_type, $start_time, $end_time, $extend_param, $page_no, $page_size)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'start_time' => $start_time,
            'end_time' => $end_time,
            '$extend_param' => $extend_param,
            'page_no' => $page_no,
            'page_size' => $page_size
        );
        $respond = $this->send('query_account_details', $post);
        return $respond;
    }

    /**
     * 冻结余额
     * @param $out_freeze_no 冻结订单号
     * @param $identity_id 用户标识信息
     * @param $account_type 账户类型
     * @param $amount 冻结金额
     * @param $summary 冻结原因
     * @return array|mixed
     */
    public function balance_freeze($out_freeze_no, $identity_id, $account_type, $amount, $summary)
    {
        $post = array(
            'out_freeze_no' => $out_freeze_no,
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'amount' => trim($amount),
            'summary' => trim($summary)
        );
        $respond = $this->send('balance_freeze', $post);
        return $respond;
    }

    /**
     * 解冻余额
     * @parem $out_unfreeze_no 解冻订单号
     * @param $out_freeze_no 需要进行解冻的原订单号
     * @param $identity_id 用户标识信息
     * @param $amount 解冻金额
     * @param $summary 解冻原因
     * @return array|mixed
     */
    public function balance_unfreeze($out_unfreeze_no, $out_freeze_no, $identity_id, $amount, $summary)
    {
        $post = array(
            'out_unfreeze_no' => $out_unfreeze_no,
            'out_freeze_no' => $out_freeze_no,
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'amount' => trim($amount),
            'summary' => trim($summary)
        );
        $respond = $this->send('balance_unfreeze', $post);
        return $respond;
    }

    /**
     * 查询冻结解冻结果
     * @param $out_ctrl_no 订单号，冻结单号或解冻单号
     * @return array|mixed
     */
    public function query_ctrl_result($out_ctrl_no)
    {
        $post = array(
            'out_ctrl_no' => $out_ctrl_no
        );
        $respond = $this->send('query_ctrl_result', $post);
        return $respond;
    }


//    3	订单类接口
    /**
     * 创建托管代收交易 （ 新浪中间账户收钱）
     * @param $out_trade_no 交易订单号
     * @param $summary 交易信息摘要
     * @param $trade_close_time 未付款交易的超时时间 （取值1m～15d）
     * @param $goods_id 商户标的号
     * @param $payer_id 用户标识信息
     * @param $payer_ip 用户请求ip
     * @param $pay_method 支付方式^金额^扩展
     * @return array|mixed
     */
    public function create_hosting_collect_trade($out_trade_no, $summary, $trade_close_time, $goods_id, $payer_id, $payer_ip, $pay_method, $payer_identity_type = "UID")
    {
        //交易码  代收投资金
        $out_trade_code = '1001';
        if ($payer_ip == "::") {
            $payer_ip = '127.0.0.1';
        }
        $post = array(
            'out_trade_no' => $out_trade_no,
            'out_trade_code' => $out_trade_code,
            'summary' => $summary,
            'trade_close_time' => $trade_close_time,
            'goods_id' => $goods_id,
            'payer_id' => $payer_id,
            'payer_identity_type' => $payer_identity_type,
            'payer_ip' => $payer_ip,
            'pay_method' => $pay_method
        );
        $respond = $this->send('create_hosting_collect_trade', $post, '2');
        return $respond;
    }

    /**
     * 创建托管代付交易（投资及满标--网站收钱）
     * @param $out_trade_no 订单号
     * @param $payee_identity_id 收款人信息标识
     * @param $account_type 收款人账户信息
     * @param $amount 收款金额
     * @param $summary 收款摘要
     * @return array|mixed
     */
    public function create_single_hosting_pay_trade($out_trade_no, $payee_identity_id, $account_type, $amount, $summary, $payee_identity_type = "UID")
    {
        //交易码  代付投资金
        $out_trade_code = "2001";
        //分账信息列表组合 --直接打到网站账户
        //$split_list = $payer_id."^"."UID"."^".$payer_account_type."^".$payee_id."^"."UID"."^".$payee_account_type."^".$pay_sum;
        $post = array(
            'out_trade_no' => $out_trade_no,
            'out_trade_code' => $out_trade_code,
            'payee_identity_id' => $payee_identity_id,
            'payee_identity_type' => $payee_identity_type,
            'account_type' => $account_type,
            'amount' => $amount,
            'summary' => $summary
        );
        $respond = $this->send('create_single_hosting_pay_trade', $post, '2');
        return $respond;
    }

    /**
     * 创建批量托管代付交易
     * @param $out_pay_no 订单号
     * @param $trade_list 交易列表
     * @param $notify_method 通知方式
     * @return array|mixed
     */
    public function create_batch_hosting_pay_trade($out_pay_no, $trade_list, $notify_method)
    {
        $out_trade_code = '2001';
        $post = array(
            'out_pay_no' => $out_pay_no,
            'out_trade_code' => $out_trade_code,
            'trade_list' => $trade_list,
            'notify_method' => $notify_method
        );
        $respond = $this->send('create_batch_hosting_pay_trade', $post, '2');
        return $respond;
    }

    /**
     * 托管退款（用户投资后网站处理失败）
     * @param $out_trade_no   交易订单号
     * @param $orig_outer_trade_no 需要退款的订单号
     * @param $refund_amount 退款的金额
     * @param $summary 退款说明
     * @return array|mixed
     */
    public function create_hosting_refund($out_trade_no, $orig_outer_trade_no, $refund_amount, $summary)
    {
        $post = array(
            'out_trade_no' => $out_trade_no,
            'orig_outer_trade_no' => $orig_outer_trade_no,
            'refund_amount' => $refund_amount,
            'summary' => $summary
        );
        $respond = $this->send('create_hosting_refund', $post, '2');
        return $respond;
    }

    /**
     * 退款根据订单查询
     * @param $identity_id 用户标识
     * @param $out_trade_no 退款订单号
     * @return array|mixed
     */
    public function query_hosting_refund_byorder($identity_id, $out_trade_no)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'out_trade_no' => $out_trade_no
        );
        $respond = $this->send('query_hosting_refund', $post, '2');
        return $respond;
    }

    /**
     * 支付结果查询
     * @param $out_pay_no 支付订单号
     * @return array|mixed
     */
    public function query_pay_result($out_pay_no)
    {
        $post = array(
            'out_pay_no' => $out_pay_no
        );
        $respond = $this->send('query_pay_result', $post);
        return $respond;
    }

    /**
     * 托管交易查询
     * @param $identity_id 用户信息标识
     * @param $out_trade_no 交易订单号 （可以为空）
     * @param $start_time 开始时间
     * @param $end_time 结束时间
     * @param $page_no 页号
     * @param $page_size 每页显示条数
     * @return array|mixed
     */
    public function query_hosting_trade($identity_id, $out_trade_no, $start_time, $end_time, $page_no, $page_size)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'out_trade_no' => $out_trade_no,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'page_no' => $page_no,
            'page_size' => $page_size
        );
        $respond = $this->send('query_hosting_trade', $post);
        return $respond;
    }

    /**
     * 托管交易批次查询
     * @param $out_batch_no 交易批次号
     * @param $page_no 页号
     * @param $page_size 每页条数
     * @return array|mixed
     */
    public function query_hosting_batch_trade($out_batch_no, $page_no, $page_size)
    {
        $post = array(
            'out_batch_no' => $out_batch_no,
            'page_no' => $page_no,
            'page_size' => $page_size
        );
        $respond = $this->send('query_hosting_batch_trade', $post);
        return $respond;
    }

    /**
     * 托管充值
     * @param $out_trade_no 交易订单号
     * @param $identity_id 用户标识信息
     * @param $account_type 用户标识类型
     * @param $amount 金额
     * @param $payer_ip 付款用户IP地址
     * @param $pay_method 支付方式
     * @param $notify_url 异步提醒
     * @return array|mixed
     */
    public function create_hosting_deposit($out_trade_no, $identity_id, $account_type, $amount, $payer_ip, $pay_method, $notify_url = '')
    {
        $post = array(
            'out_trade_no' => $out_trade_no,
            'summary' => "账户充值",
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'account_type' => $account_type,
            'amount' => $amount,
            'payer_ip' => $payer_ip,
            'pay_method' => $pay_method,
            //'notify_url' => $notify_url
        );
        $respond = $this->send('create_hosting_deposit', $post, '2');
        return $respond;
    }

    /**
     * 托管充值查询
     * @param $identity_id 用户标识信息
     * @param $account_type 账户类型
     * @param $out_trade_no 交易订单号
     * @param $start_time
     * @param $end_time 时间跨度须小于3个月
     * @param $page_no
     * @param $page_size
     * @return array|mixed
     */
    public function query_hosting_deposit($identity_id, $account_type, $out_trade_no, $start_time, $end_time, $page_no = '1', $page_size = '20')
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'out_trade_no' => $out_trade_no,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'page_no' => $page_no,
            'page_size' => $page_size
        );
        $respond = $this->send('query_hosting_deposit', $post, '2');
        return $respond;
    }

    /**
     * 托管充值查询---按订单查询
     * @param $identity_id
     * @param $account_type
     * @param $out_trade_no
     * @return array|mixed
     */
    public function query_hosting_deposit_order($identity_id,$out_trade_no,$account_type='SAVING_POT'){
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'out_trade_no' => $out_trade_no
        );
        $respond = $this->send('query_hosting_deposit',$post,'2');
        return $respond;
    }

    /**
     * 支付推进--支付确认
     * @param $out_advance_no 支付订单号
     * @param $ticket 支付凭证
     * @param $validate_code 手机验证码
     * @return array|mixed
     */
    public function advance_hosting_pay($out_advance_no, $ticket, $validate_code)
    {
        $post = array(
            'out_advance_no' => $out_advance_no,
            'ticket' => $ticket,
            'validate_code' => $validate_code
        );
        $respond = $this->send('advance_hosting_pay', $post, '2');
        return $respond;
    }

    /**
     * 托管提现
     * @param $out_trade_no 交易订单号
     * @param $identity_id 用户标识信息
     * @param $account_type 账户类型
     * @param $amount 金额
     * @return array|mixed
     */
    public function create_hosting_withdraw($out_trade_no, $identity_id, $account_type, $amount, $card_id, $notify_url = '')
    {
        $post = array(
            'out_trade_no' => $out_trade_no,
            'summary' => '提现',
            'identity_id' => $identity_id,
            'identity_type' => 'UID',
            'account_type' => $account_type,
            'amount' => $amount,
            'card_id' => $card_id,
            'notify_url' => $notify_url
        );
        $respond = $this->send('create_hosting_withdraw', $post, '2');
        return $respond;
    }

    /**
     * 托管提现查询 按订单号单笔查询
     * @param $identity_id 用户标识信息
     * @param $account_type 账户类型
     * @param $out_trade_no 交易订单号
     * @return array|mixed
     */
    public function query_hosting_withdraw_order($identity_id, $account_type, $out_trade_no)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'out_trade_no' => $out_trade_no
        );
        $respond = $this->send('query_hosting_withdraw', $post, '2');
        return $respond;
    }

    /**
     * 托管提现查询 按订单号单笔查询
     * @param $identity_id 用户标识信息
     * @param $account_type 账户类型
     * @param $start_time 交易开始时间
     * @param $end_time 交易结束时间
     * @return array|mixed
     */
    public function query_hosting_withdraw_time($identity_id, $account_type, $start_time, $end_time)
    {
        $post = array(
            'identity_id' => $identity_id,
            'identity_type' => "UID",
            'account_type' => $account_type,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        $respond = $this->send('query_hosting_withdraw', $post, '2');
        return $respond;
    }
    ///////////T+0处理
    /**
     *批量付款到银行卡
     * @param $batch_no 批次号
     * @param $detail_list 交易列表
     * @param $payto_type 到账类型
     * @return array|mixed
     */
    public function create_batch_pay2bank($batch_no, $detail_list, $payto_type = 'FAST')
    {
        $post = array(
            'batch_no' => $batch_no,
            'detail_list' => $detail_list,
            'payto_type' => $payto_type,
        );
        $respond = $this->send_two('create_batch_pay2bank', $post, '2');
        return $respond;
    }

    /**
     * 发送函数
     * @param $service
     * @param $post
     * @parem $type
     * @return array|mixed
     */
    public function send($service, $post, $type = '1')
    {
        //基本参数
        $basic_post = array(
            'service' => $service,
            'version' => $this->sina_config['sinapay_version'],
            'request_time' => date('YmdHis', time()),
            'partner_id' => $this->sina_config['sinapay_partner_id'],
            '_input_charset' => $this->sina_config['sinapay_input_charset'],
            'sign_type' => $this->sina_config['sign_type'],
            'notify_url' => 'http://101.200.88.175/mmoneynew/rpc/web/appapi/returnurl'
        );
        //基本参数与业务参数组合
        $post_data = array_merge($basic_post, $post);
        //对签名参数据排序
        ksort($post_data);
        //生成签名
        $weibopay = new Weibopay();
        $sign = $weibopay->getSignMsg($post_data, $this->sina_config['sign_type']);
        //拼接签名
        $post_data['sign'] = $sign;
        $data = $weibopay->createcurl_data($post_data);
        if ($type == '1') {
            $url = $this->sina_config['sinapay_mgs_url'];
        } else {
            $url = $this->sina_config['sinapay_mas_url'];
        }
        \Yii::error($url, "app");
        \Yii::trace($data, "app");
        $result = $weibopay->curlPost($url, $data);
        $deresult = urldecode($result);
        $splitdata = array();
        $splitdata = json_decode($deresult, true);
        ksort($splitdata); // 对签名参数据排序
        if ($weibopay->checkSignMsg($splitdata, $this->sina_config['sign_type'])) {
            if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
                return $splitdata;
            } else { // 失败
                return $splitdata;
            }
        } else {
            die ("sign error!1");
        }
    }

    /**
     * 发送函数------T+0
     * @param $service
     * @param $post
     * @parem $type
     * @return array|mixed
     */
    public function send_two($service, $post, $type = '1')
    {
        //获取商户2的基本参数
        $sina_config_two = SinaConfig::find()->where(['id' => '2'])->asArray()->one();
        if (!$sina_config_two) {
            die ("配置错误");
        }
        $basic_post = array(
            'service' => $service,
            'version' => $sina_config_two['sinapay_version'],
            'request_time' => date('YmdHis', time()),
            'partner_id' => $sina_config_two['sinapay_partner_id'],
            '_input_charset' => $sina_config_two['sinapay_input_charset'],
            'sign_type' => $sina_config_two['sign_type'],
             'notify_url' => 'http://101.200.88.175/mmoneynew/rpc/web/appapi/returnurl'
        );
        //基本参数与业务参数组合
        $post_data = array_merge($basic_post, $post);
        //对签名参数据排序
        ksort($post_data);
        //生成签名
        $weibopay = new Weibopay();
        $sign = $weibopay->getSignMsg($post_data, $sina_config_two['sign_type']);
        //拼接签名
        $post_data['sign'] = $sign;
        $data = $weibopay->createcurl_data($post_data);
        if ($type == '1') {
            $url = $sina_config_two['sinapay_mgs_url'];
        } else {
            $url = $sina_config_two['sinapay_mas_url'];
        }
        $result = $weibopay->curlPost($url, $data);
        $deresult = urldecode($result);
        $splitdata = array();
        $splitdata = json_decode($deresult, true);
        ksort($splitdata); // 对签名参数据排序
        if ($weibopay->checkSignMsg($splitdata, $sina_config_two['sign_type'])) {
            if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
                return $splitdata;
            } else { // 失败
                return $splitdata;
            }
        } else {
            die ("sign error!1");
        }
    }
}