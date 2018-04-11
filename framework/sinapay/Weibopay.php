<?php
/**
 * Created by PhpStorm.
 * User: wly
 * @copyright 万虎网络
 * Date: 2015/8/13
 * Time: 14:30
 */
namespace framework\sinapay;
    /*
     * 主配置文件 这里只配置必选和部分选填参数参数即可（如下参数均为联调环境，如果上生产需要修改对应参数）
     */
/**
 * 接口版本，新浪支付接口文档参数
 */
use common\models\sinapay\SinaConfig;

//define("sinapay_version", "1.0");//接口版本
/**
 * 商户号，由新浪支付提供
 */
//define("sinapay_partner_id","200004227922");//裸接口商户号
/**
 * 商户MD5KEY秘钥，由商户提供
 */
//define("sinapay_md5_key", "1234567890qwertyuiopasdfghjklzxc");//MD5KEY
/**
 * 商户接口字符集，新浪支付接口文档参数
 */
//define("sinapay_input_charset", "utf-8");//接口字符集编码
/**
 * 商户签名私钥，由商户自己生成
 */
//define("sinapay_rsa_sign_private_key",dirname(__File__) ."/../key/rsa_sign_private.pem");//签名私钥
/**
 * 商户验证签名公钥，由新浪提供
 */
//define("sinapay_rsa_sign_public_key",dirname ( __File__ )."/../key/rsa_sign_public.pem");//验证签名公钥
/**
 * 新浪支付特殊参数加密，公钥，由新浪支付提供
 */
//define("sinapay_rsa_public__key",dirname ( __File__ )."/../key/rsa_public.pem");//加密公钥
/**
 * 网关地址，接口文档参数
 */
//define("sinapay_mgs_url","https://testgate.pay.sina.com.cn/mgs/gateway.do");//会员类网关地址
//define("sinapay_mas_url","https://testgate.pay.sina.com.cn/mas/gateway.do");//订单类网关地址
/**
 *
 **/
define("sinapay_debug_status","true");//true 开启日志记录  false 关闭日志记录
/**
 * sftp参数配置
 */
//sftp地址
define("sinapay_sftp_address","222.73.39.37");
//sftp端口号
define("sinapay_sftp_port","50022");
//sftp登录名
define("sinapay_sftp_Username","200004227922");
//sftp登录私钥
define("sinapay_sftp_privatekey",dirname ( __File__ )."/../key/id_rsa");
//sftp登录公钥
define("sinapay_sftp_publickey",dirname ( __File__ )."/../key/id_rsa.pub");
//sftp上传目录
define("sinapay_sftp_upload_directory",dirname ( __File__ )."/upload");
class Weibopay {
    public function __construct() {
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
    function getConfig(){
        $config = SinaConfig::findOne(['id' => '1']);
        return $config;
    }
    /**
     * getSignMsg 计算前面
     *
     * @param array $pay_params
     *        	计算前面数据
     * @param string $sign_type
     *        	签名类型
     * @return string $signMsg 返回密文
     */
    function getSignMsg($pay_params = array(), $sign_type) {
        $params_str = "";
        $signMsg = "";

        foreach ( $pay_params as $key => $val ) {
            if ($key != "sign" && $key != "sign_type" && $key != "sign_version" && isset ( $val ) && @$val != "") {
                $params_str .= $key . "=" . $val . "&";
            }

        }

        $params_str = substr ( $params_str, 0, - 1 );
        switch ($this->sina_config['sign_type']) {
            case 'RSA' :
//                $priv_key = file_get_contents ( sinapay_rsa_sign_private_key );
                $priv_key = $this->sina_config['sinapay_rsa_sign_private_key'];
                $pkeyid = openssl_pkey_get_private ( $priv_key );
                openssl_sign ( $params_str, $signMsg, $pkeyid, OPENSSL_ALGO_SHA1 );
                openssl_free_key ( $pkeyid );
                $signMsg = base64_encode ( $signMsg );
                break;
            case 'MD5' :
            default :
//                $params_str = $params_str . @sinapay_md5_key;
                $params_str = $params_str . $this->sina_config['sinapay_md5_key'];
                $signMsg = strtolower ( md5 ( $params_str ) );
                break;
        }
        return $signMsg;
    }
    /**
     * 通过公钥进行rsa加密
     *
     * @param type $name
     *        	Descriptiondata
     *        	$data 需要进行rsa公钥加密的数据 必传
     *        	$pu_key 加密所使用的公钥 必传
     * @return 加密好的密文
     */
    function Rsa_encrypt($data, $public_key) {
        $encrypted = "";
//        $cert = file_get_contents ( $public_key );
        $cert = $public_key;
        $pu_key = openssl_pkey_get_public ( $cert ); // 这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt ( $data, $encrypted, $pu_key ); // 公钥加密
        $encrypted = base64_encode ( $encrypted ); // 进行编码
        return $encrypted;
    }
    /**
     * [createcurl_data 拼接模拟提交数据]
     *
     * @param array $pay_params
     * @return string url格式字符串
     */
    function createcurl_data($pay_params = array()) {
        $params_str = "";
        foreach ( $pay_params as $key => $val ) {
            if (isset ( $val ) && ! is_null ( $val ) && @$val != "") {
                $params_str .= "&" . $key . "=" . urlencode ( urlencode ( trim ( $val ) ) );
//                $params_str .= "&" . $key . "=" . trim ( $val );
            }
        }
        if ($params_str) {
            $params_str = substr ( $params_str, 1 );
        }
        return $params_str;
    }
    /**
     * checkSignMsg 回调签名验证
     *
     * @param array $pay_params
     * @param string $sign_type
     * @return boolean
     */
    function checkSignMsg($pay_params = array(), $sign_type) {
        $params_str = "";
        $signMsg = "";
        $return = false;
        foreach ( $pay_params as $key => $val ) {
            if ($key != "sign" && $key != "sign_type" && $key != "sign_version" && ! is_null ( $val ) && @$val != "") {
                $params_str .= "&" . $key . "=" . $val;
            }
        }
        if ($params_str) {
            $params_str = substr ( $params_str, 1 );
        }
        switch ($this->sina_config['sign_type']) {
            case 'RSA' :
//                $cert = file_get_contents ( sinapay_rsa_sign_public_key );
                $cert = $this->sina_config['sinapay_rsa_sign_public_key'];
                $pubkeyid = openssl_pkey_get_public ( $cert );
                $ok = openssl_verify ( $params_str, base64_decode ( $pay_params ['sign'] ), $cert, OPENSSL_ALGO_SHA1 );
                $return = $ok == 1 ? true : false;
                openssl_free_key ( $pubkeyid );
                break;
            case 'MD5' :
            default :
//                $params_str = $params_str . sinapay_md5_key;
                $params_str = $params_str .  $this->sina_config['sinapay_md5_key'];
                $signMsg = strtolower ( md5 ( $params_str ) );
                $return = (@$signMsg == @strtolower ( $pay_params ['sign'] )) ? true : false;
                break;
        }
        return $return;
    }
    /**
     * [curlPost 模拟表单提交]
     *
     * @param string $url
     * @param string $data
     * @return string $data
     */
    function curlPost($url, $data) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        $data = curl_exec ( $ch );
        curl_close ( $ch );
        return $data;
    }
    /**
     * 文件摘要算法
     */
    function md5_file($filename) {
        return md5_file ( $filename );
    }
    /**
     * sftp上传企业资质
     * sftp upload
     * @param $file 上传文件路径
     * @return FAIL 失败   SUCCESS 成功
     */
    function sftp_upload($file,$filename) {
        $strServer = sinapay_sftp_address;
        $strServerPort = sinapay_sftp_port;
        $strServerUsername = sinapay_sftp_Username;
        $strServerprivatekey = sinapay_sftp_privatekey;
        $strServerpublickey = sinapay_sftp_publickey;
        $resConnection = ssh2_connect ( $strServer, $strServerPort );
        $this->write_log($resConnection);
        if (ssh2_auth_pubkey_file ( $resConnection, $strServerUsername, $strServerpublickey, $strServerprivatekey )) {
            $resSFTP = ssh2_sftp ( $resConnection );
            file_put_contents ( "ssh2.sftp://{$resSFTP}/upload/".$filename, $file);
            if (! copy ( $file, "ssh2.sftp://{$resSFTP}/upload/$filename" )) {
                return false;
            }
        }
        return true;
    }
}