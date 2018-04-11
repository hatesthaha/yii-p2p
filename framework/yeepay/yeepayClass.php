<?php

namespace framework\yeepay;

use framework\yeepay\crypt\Crypt_RSA;
use framework\yeepay\crypt\Crypt_AES;
use framework\yeepay\crypt\Crypt_DES;
use framework\yeepay\crypt\Crypt_Hash;
use framework\yeepay\crypt\Crypt_Rijndael;
use framework\yeepay\crypt\Crypt_TripleDES;

use yii\base\Component;



class yeepayClass extends Component
{
    // CURL 参数
    public $http_info;
    public $http_header = array();
    public $http_code;
    public $useragent = 'Yeepay MobilePay PHPSDK v1.1x';
    public $connecttimeout = 30;
    public $timeout = 30;
    public $ssl_verifypeer = FALSE;
    public $url;
    // Yeepay 参数
    private $merchantAccount;
    private $merchartPublicKey;
    private $merchantPrivateKey;
    private $yeepayPublicKey;
    private $bindBankcardURL;
    private $confirmBindBankcardURL;
    private $directBindPayURL;
    private $paymentQueryURL;
    private $sendValidateCodeURL;
    private $paymentConfirmURL;
    private $withdrawURL;
    private $queryWithdrawURL;
    private $queryAuthbindListURL;
    private $bankCardCheckURL;
    private $payClearDataURL;
    private $refundURL;
    private $refundQueryURL;
    private $refundClearDataURL;
    // 加密
    private $RSA;
    private $AES;
    private $AESKey;
    // 错误
    public $error_code;
    public $error_message;
    public $system_message;

    public function __construct() {
        // 加密类
        $this->RSA = new Crypt_RSA();
        $this->RSA->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $this->RSA->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
        $this->AES = new Crypt_AES(CRYPT_AES_MODE_ECB);

        // 商户配置
        $config = array(
            'merchantAccount' => '10000419568',
            'merchantPublicKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKxayKB/TqDXtcKaObOPPzVL3r++ghEP45nai9cjG0JQt9m0F5+F8RVygizxS83iBTHd5bJbrMPLDh3GvjGm1bbJhzO4m2bF2fQm2uJ0C3ckdm9AZK8fqzcncpu2dy1zFyucFyHhKIgZryqfW5PS3G9UohS4698qA5j4dceWf5PwIDAQAB',
            'merchantPrivateKey' => 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAIrFrIoH9OoNe1wpo5s48/NUvev76CEQ/jmdqL1yMbQlC32bQXn4XxFXKCLPFLzeIFMd3lslusw8sOHca+MabVtsmHM7ibZsXZ9Cba4nQLdyR2b0Bkrx+rNydym7Z3LXMXK5wXIeEoiBmvKp9bk9Lcb1SiFLjr3yoDmPh1x5Z/k/AgMBAAECgYEAgAjVohypOPDraiL40hP/7/e1qu6mQyvcgugVcYTUmvK64U7HYHNpsyQI4eTRq1f91vHt34a2DA3K3Phzifst/RoonlMmugXg/Klr5nOXNBZhVO6i5XQ3945dUeEq7LhiJTTv0cokiCmezgdmrW8n1STZ/b5y5MIOut8Y1rwOkAECQQC+an4ako+nPNw72kM6osRT/qC589AyOav60F1bHonK6NWzWOMiFekGuvtpybgwt4jbpQxXXRPxvJkgBq873fwBAkEAupGaEcuqXtO2j0hJFOG5t+nwwnOaJF49LypboN0RX5v8nop301//P16Bs/irj5F/mAs9lFR4GZ3bxL8zs5r1PwJBALa1MDMHFlf+CcRUddW5gHCoDkjfLZJDzEVp0WoxLz5Hk2X3kFmQdHxExiCHsfjs4qD/CYx6fzyhHrygLVxgcAECQAT8z3maUDuovUCnVgzQ2/4mquEH5h8Cxe/02e46+rPrn509ZmaoMlKnXCBLjYqRATA3XLYSbAODTNS9p8wtYFECQHa/xgB+nYWoevPC/geObOLAP9HMdNVcIAJq2rgeXVI4P7cFXvksRborHmjuy1fltoR0003qlSg82mxzABbzYUs=',
            'yeepayPublicKey' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCj4k0oTc05UzrvBB6g24wsKawTlIX5995q3CQYrgM5un9mKEQc/NQIsJqqG2RUHyXUIBogMaMqG1F1QPoKMaXeVfVUSYa8ZU7bV9rOMDUT20BxAmPbtLlWdTSXDxXKXQxwkyfUAih1ZgTLI3vYg3flHeUA6cZRdbwDPLqXle8SIwIDAQAB'
        );
        $this->merchantAccount = $config['merchantAccount'];
        $this->merchartPublicKey = $config['merchantPublicKey'];
        $this->merchantPrivateKey = $config['merchantPrivateKey'];
        $this->yeepayPublicKey = $config['yeepayPublicKey'];

        // API URI 配置
        $this->bindBankcardURL = 'https://ok.yeepay.com/payapi/api/tzt/invokebindbankcard';
        $this->confirmBindBankcardURL = 'https://ok.yeepay.com/payapi/api/tzt/confirmbindbankcard';
        $this->directBindPayURL = 'https://ok.yeepay.com/payapi/api/tzt/pay/bind/reuqest';
        $this->paymentQueryURL = 'https://ok.yeepay.com/merchant/query_server/pay_single';
        $this->sendValidateCodeURL = 'https://ok.yeepay.com/payapi/api/tzt/pay/validatecode/send';
        $this->paymentConfirmURL = 'https://ok.yeepay.com/payapi/api/tzt/pay/confirm/validatecode';
        $this->withdrawURL = 'https://ok.yeepay.com/payapi/api/tzt/withdraw';
        $this->queryWithdrawURL = 'https://ok.yeepay.com/payapi/api/tzt/drawrecord';
        $this->queryAuthbindListURL = 'https://ok.yeepay.com/payapi/api/bankcard/bind/list';
        $this->bankCardCheckURL = 'https://ok.yeepay.com/payapi/api/bankcard/check';
        $this->payClearDataURL = 'https://ok.yeepay.com/merchant/query_server/pay_clear_data';
        $this->refundURL = 'https://ok.yeepay.com/merchant/query_server/direct_refund';
        $this->refundQueryURL = 'https://ok.yeepay.com/merchant/query_server/refund_single';
        $this->refundClearDataURL = 'https://ok.yeepay.com/merchant/query_server/refund_clear_data';

        // 系统错误信息
        $this->system_message = array(
            '600000' => '非法请求',
            '600004' => '接口不支持商户提交的method',
            '600010' => '系统异常',
            '600020' => '输入参数错误',
            '600042' => '无权限解绑银行卡',
            '600043' => '单卡超过当日累积支付限额',
            '600044' => '支付失败',
            '600045' => '单卡超过单笔支付限额',
            '600046' => '单卡超过单月累积支付限额',
            '600047' => '单卡超过单日累积支付次数上限',
            '600048' => '单卡超过单月累积支付次数上限',
            '600049' => '订单重复提交',
            '600050' => '订单已终态',
            '600051' => '无效的银行卡',
            '600053' => '商户不支持该卡交易',
            '600071' => '解绑失败',
            '600072' => '订单不存在',
            '600073' => '无效身份标识',
            '600074' => '卡已解绑',
            '600075' => '绑卡已过期',
            '600076' => '无效的绑卡ID',
            '600090' => '交易订单已经支付成功，不允许再发起支付请求',
            '600091' => '与银行通讯失败',
            '600092' => '查发卡方失败，请联系发卡银行',
            '600093' => '本卡在该商户不允许此交易，请联系收单机构',
            '600094' => '卡被发卡方没收，请联系发卡银行',
            '600096' => '支付失败，请联系发卡银行,银行对某些卡做了特殊的业务限制，需要用户联系银行解决',
            '600097' => '支付失败，请稍候重试',
            '600098' => '交易超限，请联系发卡银行',
            '600099' => '本卡未激活或睡眠卡，请联系发卡银行',
            '600100' => '该卡有作弊嫌疑或有相关限制，请联系发卡银行',
            '600101' => '密码错误次数超限，请联系发卡银行',
            '600102' => '可用余额不足，请联系发卡银行',
            '600103' => '该卡已过期或有效期错误，请联系发卡银行',
            '600104' => '密码验证失败，请重新输入',
            '600105' => '该卡不支持无卡支付，请联系发卡方开通',
            '600106' => '银行系统异常',
            '600107' => '商户手续费有误，请联系易宝支付',
            '600108' => '商品类别码为空或无效，请联系易宝支付',
            '600109' => '该笔交易风险较高，拒绝此次交易',
            '600110' => '订单已过期或已撤销',
            '600111' => '商户收单交易限制有误，请联系易宝支付',
            '600112' => '用户手续费有误，请联系易宝支付',
            '600113' => '订单金额太小',
            '600114' => '商户未开通该收单方式 请联系易宝运营人员确认是否已开通',
            '600115' => '交易订单信息不一致',
            '600118' => '卡已过期，请换卡重新支付',
            '600119' => '请确认身份证号是否正确',
            '600120' => '身份证、姓名或银行预留手机号有误',
            '600999' => '交易风险较高，拒绝此次交易',
            '600147' => '状态不合法',
            '600149' => '鉴权失败',
            '600156' => '用户于指定的银行卡无绑卡关系',
            '600301' => '只能绑定本人的银行卡',
            '600302' => '绑卡个数超限',
            '600303' => '只能绑定一张银行卡',
            '600304' => '请检查银行卡号是否正确',
            '600305' => '不支持此卡，请换卡',
            '600306' => '不支持信用卡的绑定',
            '600307' => '绑卡发送短信验证码次数超限',
            '600308' => '绑卡发送短信验证码频率过高',
            '600309' => '绑卡短信验证码已过期',
            '600310' => '绑卡短信验证码校验次数过多',
            '600311' => '绑卡短信验证码校验错误',
            '600312' => '绑卡请求号已存在',
            '600313' => '绑卡请求状态不合法',
            '600314' => '鉴权失败：该银行卡未开通银联在线支付业务',
            '600315' => '鉴权失败：身份证、姓名或银行预留手机号有误',
            '600316' => '鉴权失败：银行系统异常',
            '600317' => '鉴权失败：本卡未激活或睡眠卡，请联系发卡银行',
            '600318' => '鉴权失败：无效的银行卡号',
            '600319' => '鉴权失败：密码错误次数超限，请联系发卡银行',
            '600320' => '鉴权失败：银行卡被限制，详情请咨询发卡行',
            '600321' => '鉴权失败：可用余额不足，请联系发卡银行',
            '600322' => '鉴权失败：该卡已过期或有效期错误，请联系发卡银行',
            '600323' => '鉴权失败：与银行通讯失败',
            '600324' => '鉴权失败：查发卡方失败，请联系发卡银行',
            '600325' => '绑卡鉴权失败',
            '600326' => '该卡已绑定',
            '6000021' => '无对应绑卡关系',
            '6000022' => '无对应绑卡关系列表',
            '6000023' => '查询绑卡详情异常',
            '6000024' => '查询绑卡关系异常',
            '6000025' => '创建提现记录异常',
            '6000026' => '没有卡种信息',
            '6000027' => '卡种信息异常',
            '6000028' => '提现流水号为空',
            '6000036' => '提现失败',
            '6000037' => '创建提现报文失败',
            '6000038' => '更新提现报文失败',
            '6000039' => '获取提现请求异常',
            '6000040' => '商编迁移异常，请联系易宝',
            '6000041' => '商户未开通提现功能，请联系易宝',
            '6000042' => '商户订单号已经存在',
            '6000044' => '获取商户异常',
            '6000045' => '获取卡信息异常',
            '6000046' => '商户余额不足',
            '6000047' => '卡号后四位不一致',
            '6000048' => '查询绑卡详情为空',
            '6000049' => '绑卡无效',
            '200000' => '系统异常',
            '200001' => '传入参数错误或非法请求（参数错误，有必要参数为空）',
            '200002' => '没有可以返回的数据',
            '200012' => '商户账户已冻结',
            '200014' => '商户账户不存在',
            '200024' => '交互解密失败',
            '200025' => 'sign验签失败',
            '200026' => '请求失败，请检查参数格式',
            '200027' => '查不到此交易订单',
            '200038' => '接口不支持商户提交的 method',
            '200039' => '时间间隔超过 31天',
            '200040' => '记录数量过多，请尝试缩短日期间隔',
            '600116' => '输入验证码错误'
        );
    }

    /**
     * 获取商户编号
     * @return string
     */
    public function getMerchartAccount() {
        return trim($this->merchantAccount);
    }

    /**
     * 获取商户私匙
     * @return string
     */
    public function getMerchantPrivateKey() {
        return $this->merchantPrivateKey;
    }

    /**
     * 获取商户AESKey
     * @return string
     */
    public function getmerchantAESKey() {
        return $this->random(16, 1);
    }

    /**
     * 获取易宝公匙
     * @return string
     */
    public function getYeepayPublicKey() {
        return $this->yeepayPublicKey;
    }

    /** 生成随机字符串
     * @param $length 字符串长度
     * @param int $numeric 生成的类型 0--数字字母 其他--数字
     * @return string
     */
    public function random($length, $numeric = 0) {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * 绑卡请求接口请求地址
     * @return string
     */
    public function getBindBankcardURL() {
        return $this->bindBankcardURL;
    }

    /**
     * 绑卡确认接口请求地址
     * @return string
     */
    public function getConfirmBindBankcardURL() {
        return $this->confirmBindBankcardURL;
    }

    /**
     * 支付接口请求地址
     * @return string
     */
    public function getDirectBindPayURL() {
        return $this->directBindPayURL;
    }

    /**
     * 订单查询请求地址
     * @return string
     */
    public function getPaymentQueryURL() {
        return $this->paymentQueryURL;
    }

    /**
     * 发送短信验证码地址
     * @return string
     */
    public function getSendValidateCodeURL() {
        return $this->sendValidateCodeURL;
    }

    /**
     * 确定支付请求地址
     * @return string
     */
    public function getpaymentConfirmURL() {
        return $this->paymentConfirmURL;
    }

    /**
     * 取现接口请求地址
     * @return string
     */
    public function getWithdrawURL() {
        return $this->withdrawURL;
    }

    /**
     * 取现查询请求地址
     * @return string
     */
    public function getQueryWithdrawURL() {
        return $this->queryWithdrawURL;
    }

    /**
     * 取现查询请求地址
     * @return string
     */
    public function getQueryAuthbindListURL() {
        return $this->queryAuthbindListURL;
    }

    /**
     * 银行卡信息查询请求地址
     * @return string
     */
    public function getBankCardCheckURL() {
        return $this->bankCardCheckURL;
    }

    /**
     * 支付清算文件下载请求地址
     * @return string
     */
    public function getPayClearDataURL() {
        return $this->payClearDataURL;
    }

    /**
     * 单笔退款请求地址
     * @return string
     */
    public function getRefundURL() {
        return $this->refundURL;
    }

    /**
     * 退款查询请求地址
     * @return string
     */
    public function getRefundQueryURL() {
        return $this->refundQueryURL;
    }

    /**
     * 退款清算文件请求地址
     * @return string
     */
    public function getRefundClearDataURL() {
        return $this->refundClearDataURL;
    }

    /**
     * 绑定银行卡
     * @param $identityid
     * @param $requestid
     * @param $cardno
     * @param $idcardno
     * @param $username
     * @param $phone
     * @param $userip
     * @param string $os
     * @param string $ua
     * @param string $imei
     * @param string $registerphone
     * @param string $registerdate
     * @param string $registerip
     * @param string $registeridcardno
     * @param string $registercontact
     * @return type
     */
    public function bindBankcard($identityid, $requestid, $cardno, $idcardno, $username, $phone, $userip, $os = '', $ua = '', $imei = '', $registerphone = '', $registerdate = '', $registerip = '', $registeridcardno = '', $registercontact = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'identityid' => trim($identityid),
            'identitytype' => intval(2),
            'requestid' => trim($requestid),
            'cardno' => trim($cardno),
            'idcardtype' => trim('01'),
            'idcardno' => trim($idcardno),
            'username' => trim($username),
            'phone' => trim($phone),
            'registerphone' => trim($registerphone),
            'registerdate' => trim($registerdate),
            'registerip' => trim($registerip),
            'registeridcardtype' => trim('01'),
            'registeridcardno' => trim($registeridcardno),
            'registercontact' => trim($registercontact),
            'os' => trim($os),
            'imei' => trim($imei),
            'userip' => trim($userip),
            'ua' => trim($ua)
        );
        return $this->post($this->getBindBankcardURL(), $query);
    }

    /**
     * 确定绑卡
     * @param type $requestid
     * @param type $validatecode
     * @return type
     */
    public function bindBankcardConfirm($requestid, $validatecode) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'requestid' => trim($requestid),
            'validatecode' => trim($validatecode)
        );
        return $this->post($this->getConfirmBindBankcardURL(), $query);
    }

    /**
     * 获取绑卡记录
     * @param type $identityid
     * @return type
     */
    public function bankcardList($identityid) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'identityid' => trim($identityid),
            'identitytype' => intval(2)
        );
        return $this->get($this->getQueryAuthbindListURL(), $query);
    }

    /**
     * 获取绑卡支付请求
     * @param type $orderid
     * @param type $transtime
     * @param type $amount
     * @param type $productname
     * @param type $identityid
     * @param type $card_top
     * @param type $card_last
     * @param type $callbackurl
     * @param type $userip
     * @param type $orderexpdate
     * @param type $productdesc
     * @param type $imei
     * @param type $ua
     * @return type
     */
    public function directPayment($orderid, $transtime, $amount, $productname, $identityid, $card_top, $card_last, $callbackurl, $userip, $orderexpdate = 20, $productdesc = '', $imei = '', $ua = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'orderid' => trim($orderid),
            'transtime' => intval($transtime),
            'currency' => intval(156),
            'amount' => intval($amount),
            'productname' => trim($productname),
            'productdesc' => trim($productdesc),
            'identityid' => trim($identityid),
            'identitytype' => intval(2),
            'card_top' => trim($card_top),
            'card_last' => trim($card_last),
            'orderexpdate' => intval($orderexpdate),
            'callbackurl' => trim($callbackurl),
            'imei' => trim($imei),
            'userip' => trim($userip),
            'ua' => trim($ua)
        );
//         return $query;
        return $this->post($this->getDirectBindPayURL(), $query);
    }

    /**
     * 发送支付验证码
     * @param type $orderid
     * @return type
     */
    public function sendValidateCode($orderid) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'orderid' => trim($orderid)
        );
        return $this->post($this->getSendValidateCodeURL(), $query);
    }

    /**
     * 确认支付
     * @param type $orderid
     * @param type $validatecode
     * @return type
     */
    public function confirmPayment($orderid, $validatecode = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'orderid' => trim($orderid),
            'validatecode' => trim($validatecode)
        );
        return $this->post($this->getpaymentConfirmURL(), $query);
    }

    /**
     *
     * 交易记录查询
     * @param string $orderid
     * @param string $yborderid
     * @return array
     */
    public function paymentQuery($orderid = '', $yborderid = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'orderid' => trim($orderid),
            'yborderid' => trim($yborderid)
        );
        return $this->get($this->getPaymentQueryURL(), $query);
    }

    /**
     * 提现操作
     * @param $requestid
     * @param $identityid
     * @param $card_top
     * @param $card_last
     * @param $amount
     * @param $userip
     * @param string $imei
     * @param string $ua
     * @return type
     */
    public function withdraw($requestid, $identityid, $card_top, $card_last, $amount, $userip, $imei = '', $ua = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'requestid' => trim($requestid),
            'identityid' => trim($identityid),
            'identitytype' => intval(2),
            'card_top' => trim($card_top),
            'card_last' => trim($card_last),
            'amount' => intval($amount),
            'currency' => intval(156),
            //提现类型 NATRALDAY_NORMAL( 自 然 日t+1);  NATRALDAY_URGENT（ 自然日 t+0）
            'drawtype' => trim('NATRALDAY_URGENT'),
            'imei' => trim($imei),
            'userip' => trim($userip),
            'ua' => trim($ua)
        );
        return $this->post($this->getWithdrawURL(), $query);
    }

    /**
     * 银行卡信息查询
     * @param type $cardno
     * @return type
     */
    public function bankcardCheck($cardno) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'cardno' => trim($cardno)
        );
        return $this->post($this->getBankCardCheckURL(), $query);
    }

    /**
     * 提现查询
     * @param type $requestid
     * @param type $ybdrawflowid
     * @return type
     */
    public function withdrawQuery($requestid, $ybdrawflowid = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'requestid' => trim($requestid),
            'ybdrawflowid' => trim($ybdrawflowid)
        );
        return $this->get($this->getQueryWithdrawURL(), $query);
    }

    /**
     * 获取支付清算文件
     * @param type $startdate eg:2015-06-01
     * @param type $enddate eg:2016-06-30
     * @return type
     */
    public function payClearData($startdate, $enddate) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'startdate' => trim($startdate),
            'enddate' => trim($enddate)
        );

        $url = $this->getUrl($this->getPayClearDataURL(), $query);
        $data = $this->http($url, 'GET');
        if ($this->http_info['http_code'] == 405) {
            $this->error_code = '600004';
            return $this->get_error_message();
        }
        return explode($this->getMerchartAccount(),$data);
        return $data;
    }

    /**
     * 退款接口
     * @param type $amount
     * @param type $orderid
     * @param type $origyborderid
     * @param type $cause
     * @return type
     */
    public function refund($amount, $orderid, $origyborderid, $cause = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'amount' => intval($amount),
            'currency' => intval(156),
            'cause' => trim($cause),
            'orderid' => trim($orderid),
            'origyborderid' => trim($origyborderid)
        );
        return $this->post($this->getRefundURL(), $query);
    }

    /**
     * 退款记录查询接口
     * @param type $orderid
     * @param type $yborderid
     * @return type
     */
    public function refundQuery($orderid, $yborderid = '') {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'orderid' => trim($orderid),
            'yborderid' => trim($yborderid)
        );
        return $this->get($this->getRefundQueryURL(), $query);
    }

    /**
     * 获取退款清算文件对账记录
     * @param type $startdate eg:2013-05-01
     * @param type $enddate eg:2013-05-30
     * @return type
     */
    public function refundQueryClearData($startdate, $enddate) {
        $query = array(
            'merchantaccount' => $this->getMerchartAccount(),
            'startdate' => trim($startdate),
            'enddate' => trim($enddate)
        );

        $url = $this->getUrl($this->getRefundClearDataURL(), $query);
        $data = $this->http($url, 'GET');
        if ($this->http_info['http_code'] == 405) {
            $this->error_code = '600004';
            return $this->get_error_message();
        }
        return explode( $this->getMerchartAccount(),$data);
        return $data;
    }

    /**
     *
     * @param string $url
     * @param type $query
     * @return string
     */
    public function getUrl($url, $query) {
        $request = $this->buildRequest($query);
        $url .= '?' . http_build_query($request);
        return $url;
    }

    public function buildRequest($query) {
        if (!array_key_exists('merchantaccount', $query)) {
            $query['merchantaccount'] = $this->getMerchartAccount();
        }
        $sign = $this->RSASign($query);
        $query['sign'] = $sign;
        $request = array();
        $request['merchantaccount'] = $this->getMerchartAccount();
        $request['encryptkey'] = $this->getEncryptkey();
        $request['data'] = $this->AESEncryptRequest($query);
        return $request;
    }

    /**
     * 用RSA 签名请求
     * @param array $query
     * @return string
     */
    protected function RSASign(array $query) {
        if (array_key_exists('sign', $query)) {
            unset($query['sign']);
        }
        ksort($query);
        $this->RSA->loadKey($this->getMerchantPrivateKey());
        $sign = base64_encode($this->RSA->sign(join('', $query)));
        return $sign;
    }

    /**
     * 通过RSA，使用易宝公钥，加密本次请求的AESKey
     * @return string
     */
    protected function getEncryptkey() {
        if (!$this->AESKey) {
            $this->generateAESKey();
        }
        $this->RSA->loadKey($this->yeepayPublicKey);
        $encryptKey = base64_encode($this->RSA->encrypt($this->AESKey));
        return $encryptKey;
    }

    /**
     * 生成一个随机的字符串作为AES密钥
     * @param number $length
     * @return string
     */
    protected function generateAESKey($length = 16) {
        $baseString = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $AESKey = '';
        $_len = strlen($baseString);
        for ($i = 1; $i <= $length; $i++) {
            $AESKey .= $baseString[rand(0, $_len - 1)];
        }
        $this->AESKey = $AESKey;
        return $AESKey;
    }

    /**
     * 返回易宝返回数据的AESKey
     * @param unknown $encryptkey
     * @return Ambigous <string, boolean, unknown>
     */
    protected function getYeepayAESKey($encryptkey) {
        $this->RSA->loadKey($this->merchantPrivateKey);
        $yeepayAESKey = $this->RSA->decrypt(base64_decode($encryptkey));
        return $yeepayAESKey;
    }

    /**
     * 通过AES加密请求数据
     * @param array $query
     * @return string
     */
    protected function AESEncryptRequest(array $query) {
        if (!$this->AESKey) {
            $this->generateAESKey();
        }
        $this->AES->setKey($this->AESKey);
        return base64_encode($this->AES->encrypt(json_encode($query)));
    }

    /**
     * 模拟HTTP协议
     * @param string $url
     * @param string $method
     * @param string $postfields
     * @return mixed
     */
    protected function http($url, $method, $postfields = NULL) {
        $this->http_info = array();
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        $method = strtoupper($method);
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
            case 'DELETE':
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($postfields)) {
                    $url = "{$url}?{$postfields}";
                }
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
        $this->url = $url;
        curl_close($ci);
        return $response;
    }

    /**
     * Get the header info to store.
     * @param type $ch
     * @param type $header
     * @return type
     */
    public function getHeader($ch, $header) {
        $i = strpos($header, ':');
        if (!empty($i)) {
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }
        return strlen($header);
    }

    /**
     * 解析返回数据
     * @param type $data
     * @return type
     */
    protected function parseReturnData($data) {
        $return = json_decode($data, true);
        if (array_key_exists('error_code', $return) && !array_key_exists('status', $return)) {
            $this->error_code = $return['error_code'];
            return $this->get_error_message();
        }
        //返回数据
        return $this->parseReturn($return['data'], $return['encryptkey']);
    }

    /**
     * 解析返回数据
     * @param type $data
     * @param type $encryptkey
     * @return type
     */
    protected function parseReturn($data, $encryptkey) {
        $AESKey = $this->getYeepayAESKey($encryptkey);
        $return = $this->AESDecryptData($data, $AESKey);
        $return = json_decode($return, true);
        if (!array_key_exists('sign', $return)) {
            if (array_key_exists('error_code', $return)) {
                $this->error_code = $return['error_code'];
                return $this->get_error_message();
            }
            $this->error_code = '600010';
            return $this->get_error_message();
        } else {
            if (!$this->RSAVerify($return, $return['sign'])) {
                $this->error_code = '200025';
                return $this->get_error_message();
            }
        }
        if (array_key_exists('error_code', $return) && !array_key_exists('status', $return)) {
            $this->error_code = $return['error_code'];
            return $this->get_error_message();
        }
        unset($return['sign']);
        return $return;
    }

    /**
     * 通过AES解密易宝返回的数据
     * @param string $data
     * @param string $AESKey
     * @return Ambigous <boolean, string, unknown>
     */
    protected function AESDecryptData($data, $AESKey) {
        $this->AES->setKey($AESKey);
        return $this->AES->decrypt(base64_decode($data));
    }

    /**
     * 使用易宝公钥检测易宝返回数据签名是否正确
     * @param array $return
     * @param $sign
     * @return bool
     */
    protected function RSAVerify(array $return, $sign) {
        if (array_key_exists('sign', $return)) {
            unset($return['sign']);
        }
        ksort($return);
        $this->RSA->loadKey($this->yeepayPublicKey);
        foreach ($return as $k => $val) {
            if (is_array($val)) {
                $return[$k] = self::cn_json_encode($val);
            }
        }
        return $this->RSA->verify(join('', $return), base64_decode($sign));
    }

    /**
     * json_encode
     * @param type $value
     * @return type
     */
    public static function cn_json_encode($value) {
        if (defined('JSON_UNESCAPED_UNICODE')) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $encoded = urldecode(json_encode(self::array_urlencode($value)));
            return preg_replace(array('/\r/', '/\n/'), array('\\r', '\\n'), $encoded);
        }
    }

    /**
     * urlencode
     * @param type $value
     * @return type
     */
    public static function array_urlencode($value) {
        if (is_array($value)) {
            return array_map(array('yeepay', 'array_urlencode'), $value);
        } elseif (is_bool($value) || is_numeric($value)) {
            return $value;
        } else {
            return urlencode(addslashes($value));
        }
    }

    /**
     * 使用POST的方式发出API请求
     * @param type $url
     * @param type $query
     * @return type
     */
    protected function post($url, $query) {
        $request = $this->buildRequest($query);
        $data = $this->http($url, 'POST', http_build_query($request));
        if ($this->http_info['http_code'] == 405) {
            $this->error_code = '600004';
            return $this->get_error_message();
        }
        return $this->parseReturnData($data);
    }

    /**
     * 使用GET的模式发出API请求
     * @param $url
     * @param $query
     * @return type|mixed
     */
    protected function get($url, $query) {
        $request = $this->buildRequest($query);
        $url .= '?' . http_build_query($request);
        $data = $this->http($url, 'GET');
        if ($this->http_info['http_code'] == 405) {
            $this->error_code = '600004';
            return $this->get_error_message();
        }
        return $this->parseReturnData($data);
    }

    /**
     * 返回错误信息
     * @param string $error_code
     * @return mixed
     */
    public function get_error_message($error_code = '') {
        $e = $error_code ? $error_code : $this->error_code;
        return $this->system_message[$e];
    }

    /**
     * 返回错误代码
     * @return mixed
     */
    public function get_error_code() {
        return $this->error_code;
    }

    /**
     * 回调返回数据解析函数
     * @param $data
     * @param $encryptkey
     * @return type
     */
    public function callback($data,$encryptkey){
        return $this->parseReturn($data, $encryptkey);
    }

}