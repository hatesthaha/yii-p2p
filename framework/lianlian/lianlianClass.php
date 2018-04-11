<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/7/31
 * Time: 11:21
 */
namespace framework\lianlian;

use common\models\base\asset\Info;
use framework\lianlian\lib\LLpaySubmit;
use yii\base\Component;
use framework\lianlian\lib\LLpayNotify;
use common\models\lianlian\payLL;
use framework\lianlian\lib\JSON;
use common\models\base\asset\Log;
class lianlianClass extends Component{
    const CONFIRM = -1;
    const ERROR = 0;
    const SUCCESS = 1;
    //用户配置字段
    //商户编号是商户在连连钱包支付平台上开设的商户号码，为18位数字，如：201306081000001016
    private $oid_partner ;
    //安全检验码，以数字和字母组成的字符
    private $key;
    //版本号
    private $version;
    //防钓鱼ip 可不传或者传下滑线格式
    private $userreq_ip;
    //证件类型
    private $id_type;
    //签名方式 不需修改
    private $sign_type;
    //订单有效时间  分钟为单位，默认为10080分钟（7天）
    private $valid_order;
    //字符编码格式 目前支持 gbk 或 utf-8
    private $input_charset;
    //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    private $transport;

    ///后加
    private $llpay_config;
    //支付方式
    private $pay_type;
    //风险控制参数
    private $risk_item;

    public function __construct() {
        //用户配置
        $config = array(
            'oid_partner' => '201408071000001541',
            'key' => '201408071000001541_sadqurnf_20141203',
            'version' => '1.0',
            'userreq_ip' => '10_10_246_110',
            'id_type' => '0',
            'sign_type' => strtoupper('md5'),
            'valid_order' => '10080',
            'input_charset' => strtolower('utf-8'),
            'transport' => 'http'
        );
        $this->llpay_config = $config;

        $this->oid_partner = $config['oid_partner'];
        $this->key = $config['key'];
        $this->version = $config['version'];
        $this->userreq_ip = $config['userreq_ip'];
        $this->id_type = $config['id_type'];
        $this->sign_type = $config['sign_type'];
        $this->valid_order = $config['valid_order'];
        $this->input_charset = $config['input_charset'];
        $this->transport = $config['transport'];
    }

    /**
     * 用户确认支付接口
     * @param $user_id 用户唯一标示符
     * @param $busi_partner 商户业务类型
     * @param $no_order 商户唯一订单号
     * @param $name_goods 商品名称
     * @param $money_order 订单金额
     * @param $notify_url 异步通知
     * @param $id_no 证件号码
     * @param $acct_name 真实姓名
     * @param $card_no 银行卡号
     * @param $bank_code 银行编码
     * @return lib\提交表单HTML文本
     */
    public function confirmation($user_id,$busi_partner,$no_order,$name_goods,$money_order,$notify_url,$id_no,$acct_name,$card_no,$bank_code){
        $risk_item = array(
            'frms_ware_category' => '2009',
            'user_info_mercht_userno' => $user_id,
            'user_info_dt_register' => date("YmdHis", time())
        );
        $risk_item = json_encode($risk_item);
        $parameter = array (
            "version" => trim($this->llpay_config['version']),
            "oid_partner" => trim($this->llpay_config['oid_partner']),
            "sign_type" => trim($this->llpay_config['sign_type']),
            "userreq_ip" => trim($this->llpay_config['userreq_ip']),
            "id_type" => trim($this->llpay_config['id_type']),
            "valid_order" => trim($this->llpay_config['valid_order']),
            "user_id" => $user_id,
            "timestamp" => date('YmdHis', time()),
            "busi_partner" => $busi_partner,
            "no_order" => $no_order,
            "dt_order" => date('YmdHis', time()),
            "name_goods" => $name_goods,
            "money_order" => $money_order,
            "notify_url" => $notify_url,
            "bank_code" => $bank_code,
            "risk_item" => $risk_item,
            "id_no" => $id_no,
            "acct_name" => $acct_name,
            "card_no" => $card_no,
            "url_return" => 'http://101.200.88.175/mmoney/www/web/money/returnurl'
        );
        $llpaySubmit = new LLpaySubmit($this->llpay_config);
        $html_text = $llpaySubmit->buildRequestForm($parameter, "post", '');
        return $html_text;
    }
    //支付同步返回方法
    public function urlReturn(){
       $llpayNotify = new LLpayNotify($this->llpay_config);
       $verify_result = $llpayNotify->verifyReturn();
        ///验证成功
        if(!$verify_result['errorNum']){
            $oid_partner = $_POST['oid_partner' ];
            //签名方式
            $sign_type = $_POST['sign_type' ];
            //签名
            $sign= $_POST['sign' ];
            //商户订单时间
            $dt_order= $_POST['dt_order' ];
            //商户订单号
            $no_order = $_POST['no_order' ];
            //支付单号
            $oid_paybill = $_POST['oid_paybill' ];
            //交易金额
            $money_order = $_POST['money_order' ];
            //支付结果
            $result_pay =  $_POST['result_pay'];
            //清算日期
            $settle_date =  $_POST['settle_date'];
            //订单描述
            $info_order =  $_POST['info_order'];
            //支付方式
            $pay_type =  $_POST['pay_type'];
            //银行编号
            $bank_code =  $_POST['bank_code'];
            //获取订单号
            $pay = payLL::findOne([
                'no_order' => $no_order,
                'status' => self::CONFIRM,
            ]);
            if($pay){
                if($result_pay == 'SUCCESS'){
                    //验证支付金额
                    if($pay->money_order == $money_order){
                        //支付成功了--更新数据
                        $pay->status = self::SUCCESS;
                        $pay->remark = "success";
                        $pay->sign_type = $sign_type;
                        $pay->sign = $sign;
                        $pay->oid_paybill = $oid_paybill;
                        $pay->settle_date = $settle_date;
                        $pay->money_lianlian = $money_order;
                        $pay->pay_type = $pay_type;
                        $pay->bank_code = $bank_code;
                        $res = $pay->save();
                        if($res){

                            $card_no = $pay->card_no;
                            $uid = $pay->uid;

                            $info = Info::find()->where(['member_id' => $uid])->one();
                            $newmoney = $info->balance;
                            $info->balance = $newmoney + $money_order;
                            $info->bank_card = $card_no;
                            $info->bank_card_phone = $bank_code;

                            if($info->save()){
                                //添加网站充值记录
                                $log = self::logSave($uid,$money_order,"setBalabce",Log::STATUS_RECHAR_SUC,$card_no,"充值成功");
                                if($log){
                                    $data = array(
                                        'uid' => $uid,
                                        'money' => $info->balance
                                    );
                                    return $data;
                                }else{
                                    return false;
                                }

                            }else{
                                return false;
                            }

                        }else{
                            return false;
                        }
                    }else{
                        $pay->status = self::ERROR;
                        $pay->remark = "订单金额错误";
                        $pay->sign_type = $sign_type;
                        $pay->sign = $sign;
                        $pay->oid_paybill = $oid_paybill;
                        $pay->settle_date = $settle_date;
                        $pay->money_lianlian = $money_order;
                        $pay->pay_type = $pay_type;
                        $pay->bank_code = $bank_code;
                        $res = $pay->save();
                        return false;
                    }
                }else{
                    //支付失败了
                    $pay->status = self::ERROR;
                    $pay->save();
                    return false;
                }
            }else{
                //无订单信息
                return false;
            }
        }else{
            //验证失败了
            return false;
        }
    }
    //支付结果异步通知
    public function returnNotify(){
        $llpayNotify = new LLpayNotify($this->llpay_config);
        $verify_result = $llpayNotify->verifyNotify();
        if ($verify_result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取连连支付的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $is_notify = true;
            $json = new JSON;
            $str = file_get_contents("php://input");
            $val = $json->decode($str);
            $oid_partner = trim($val-> {'oid_partner' });
            $dt_order = trim($val-> {'dt_order' });
            $no_order = trim($val-> {'no_order' });
            $oid_paybill = trim($val-> {'oid_paybill' });
            $money_order = trim($val-> {'money_order' });
            $result_pay = trim($val-> {'result_pay' });
            $settle_date = trim($val-> {'settle_date' });
            $info_order = trim($val-> {'info_order' });
            $pay_type = trim($val-> {'pay_type' });
            $bank_code = trim($val-> {'bank_code' });
            $sign_type = trim($val-> {'sign_type' });
            $sign = trim($val-> {'sign' });
            ///如果同步接受操作没有处理成功
            $pay = payLL::findOne([
                'no_order' => $no_order,
                'status' => self::CONFIRM,
            ]);
            if($pay) {
                if ($result_pay == 'SUCCESS') {
                    //验证支付金额
                    if ($pay->money_order == $money_order) {
                        //支付成功了--更新数据
                        $pay->status = self::SUCCESS;
                        $pay->remark = "success";
                        $pay->sign_type = $sign_type;
                        $pay->sign = $sign;
                        $pay->oid_paybill = $oid_paybill;
                        $pay->settle_date = $settle_date;
                        $pay->money_lianlian = $money_order;
                        $pay->pay_type = $pay_type;
                        $pay->bank_code = $bank_code;
                        $res = $pay->save();
                        if ($res) {
                            $card_no = $pay->card_no;
                            $uid = $pay->uid;

                            $info = Info::find()->where(['member_id' => $uid])->one();
                            $newmoney = $info->balance;
                            $info->balance = $newmoney + $money_order;
                            $info->bank_card = $card_no;
                            $info->bank_card_phone = $bank_code;
                            $info->save();
                        }
                    }else{
                        $pay->status = self::ERROR;
                        $pay->remark = "订单金额错误";
                        $pay->sign_type = $sign_type;
                        $pay->sign = $sign;
                        $pay->oid_paybill = $oid_paybill;
                        $pay->settle_date = $settle_date;
                        $pay->money_lianlian = $money_order;
                        $pay->pay_type = $pay_type;
                        $pay->bank_code = $bank_code;
                        $pay->save();
                    }

                }
            }

            file_put_contents("log.txt", "异步通知 验证成功\n", FILE_APPEND);
            die("{'ret_code':'0000','ret_msg':'交易成功'}"); //请不要修改或删除
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            file_put_contents("log.txt", "异步通知 验证失败\n", FILE_APPEND);
            //验证失败
            die("{'ret_code':'9999','ret_msg':'验签失败'}");
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * 商户支付结果查询
     * @param $no_order 商户唯一订单号
     * @param $dt_order 商户订单时间
     * @param $oid_paybill 连连支付支付单号
     */
    public function orderQuery($no_order,$dt_order,$oid_paybill){
        $oid_partner = trim($this->llpay_config['oid_partner']);
        $sign_type = trim($this->llpay_config['sign_type']);
        $query_version = trim($this->llpay_config['valid_order']);
        $parameter = array(
            'oid_partner' => $oid_partner,
            'sign_type' => $sign_type,
            'no_order' => $no_order,
            'dt_order' => $dt_order,
            'oid_paybill' => $oid_paybill,
            'query_version' => $query_version
        );
        $llpaySubmit = new LLpaySubmit($this->llpay_config);
        $post_data = $llpaySubmit->buildRequestPara($parameter);
        $post_json = json_encode($post_data);
        $url = "https://yintong.com.cn/traderapi/orderquery.htm";
        $result = $this->vpost($url,$post_json);
        return json_decode($result,true);
    }


    /**
     * 银行卡的卡 BIN
     * @param $card_no
     * @return mixed
     */
    public function bankcardQuery($card_no){
        $oid_partner = trim($this->llpay_config['oid_partner']);
        $sign_type = trim($this->llpay_config['sign_type']);
        $pay_type = "D";
        $flag_amt_limit = 1;
        $parameter = array(
            'oid_partner' => $oid_partner,
            'sign_type' => $sign_type,
            'card_no' => trim($card_no),
            'pay_type' => $pay_type,
            'flag_amt_limit' => $flag_amt_limit
        );
        $llpaySubmit = new LLpaySubmit($this->llpay_config);
        $post_data = $llpaySubmit->buildRequestPara($parameter);
        $post_json = json_encode($post_data);
        $url = "https://yintong.com.cn/traderapi/bankcardquery.htm";
        $result = $this->vpost($url,$post_json);
        return json_decode($result,true);
    }

    /**
     * 用户签约信息查询
     * @param $user_id 网站用户唯一标识
     * @return mixed
     */
    public function userBankcard($user_id){
        $oid_partner = trim($this->llpay_config['oid_partner']);
        $pay_type = "D";
        $sign_type = trim($this->llpay_config['sign_type']);
        $offset = "0";
        $parameter = array(
            'oid_partner' => $oid_partner,
            'sign_type' => $sign_type,
            'pay_type' => $pay_type,
            'offset' => $offset,
            'user_id' => $user_id
        );
        $llpaySubmit = new LLpaySubmit($this->llpay_config);
        $post_data = $llpaySubmit->buildRequestPara($parameter);
        $post_json = json_encode($post_data);
        $url = "https://yintong.com.cn/traderapi/userbankcard.htm";
        $result = $this->vpost($url,$post_json);
        return json_decode($result,true);
    }

    /**
     * 银行卡解约 解约后不再能查询订单信息
     * @param $user_id 网站用户唯一标识
     * @param $no_agree 银行卡签约的唯一编号
     * @return mixed
     */
    public function bankcardunbind($user_id,$no_agree){
        $oid_partner = trim($this->llpay_config['oid_partner']);
        $pay_type = "D";
        $sign_type = trim($this->llpay_config['sign_type']);
        $parameter = array(
            'oid_partner' => $oid_partner,
            'sign_type' => $sign_type,
            'pay_type' => $pay_type,
            'no_agree' => $no_agree,
            'user_id' => $user_id
        );
        $llpaySubmit = new LLpaySubmit($this->llpay_config);
        $post_data = $llpaySubmit->buildRequestPara($parameter);
        $post_json = json_encode($post_data);
        $url = "https://yintong.com.cn/traderapi/bankcardunbind.htm";
        $result = $this->vpost($url,$post_json);
        return json_decode($result,true);
    }
    /**
     *  https post 方式提交
     * @param $url
     * @param $data
     * @return mixed
     */
    function vpost($url,$data){ // 模拟提交数据函数
        $url = trim($url);
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查--设定为不验证证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }

    /**
     * 保存用户的账户操作记录
     * @param $member_id
     * @param $step
     * @param $action
     * @param $status
     * @param $bankcard
     * @param $remark
     * @return bool
     */
    public  static function logSave($member_id,$step,$action,$status,$bankcard,$remark){
        $log = New Log();
        $log->member_id = $member_id;
        $log->step = $step;
        $log->action = $action;
        $log->status = $status;
        $log->bankcard = $bankcard;
        $log->remark = $remark;
        $res = $log->save();
        return $res;
    }



}