<?php
namespace framework\App;
use yii\base\ErrorException;
use yii\base\Component;
class Appcheck  extends Component
{

    public $pubkey;
    public $privkey;

    function __construct()
    {
        $this->pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDZhcBdTgic19mPV1fGN4sQmYrZ
aIWTATgLy3WxdkNdE593mzmC+FOBA2dtlOi5tx65CQh3twaVQXfZ81aGBHVqEiRl
Lf9Aw1JtqredbIboiIy9rW1sVodG56UJuqNy0e0W4HmDnpNlurb2bZUk1GRR0c8o
brsDye8ityFATOYKsQIDAQAB
-----END PUBLIC KEY-----';

        $this->privkey = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDZhcBdTgic19mPV1fGN4sQmYrZaIWTATgLy3WxdkNdE593mzmC
+FOBA2dtlOi5tx65CQh3twaVQXfZ81aGBHVqEiRlLf9Aw1JtqredbIboiIy9rW1s
VodG56UJuqNy0e0W4HmDnpNlurb2bZUk1GRR0c8obrsDye8ityFATOYKsQIDAQAB
AoGBAKB+el+dqucmsAR+OmorIzVdRMCCyUOc+6BgP3dvveZYCyox4q5K0Tn4rACD
hW85uLZn/tJtvbK0ulyambWKZqI/g0Mf7TRG/bqSwyleFRnYrurWKpa2edUQ5PZt
mXdzP/tQ7z1hyMx3Yw/edw2P8M3h4od/CrFzNXjQI3Ut2OrdAkEA7RXQ7PgV2SHs
pHZD+BfYk/zJtw9Vbbqygv7URfFP8pMn8BHndMbedAz4+w6CSkYeUsxnijNPooRI
W+pMniTIAwJBAOrgY6sYWflFxeTQN6qERWwgllGJTRxfRJRe7nwB9iolKczZ87aF
xTjkHQunt9Uuo/j8h+MjACRPZFXS4TA+pjsCQClsxWuSUWb2CX4W9/tSV1JMOgaV
VEzu8R/4XKi36EsvMSw+RJ3f3aCTX7GmMFe/9q5q7rB0FLcFvArrXmXHcXcCQDCD
0vKHYCZWHC9MNwNYuQJBpOsIok3m+8Jt7XLd8Nv5uy2eI3IjJX6/16QNq7I0JRiM
CQGIvEHDyGTV3z/Y4PsCQAHGdEukwlBZrwp8/5kUkyxWuEvLBudOpKGO5WPrTkUQ
0TAY8UUeKXYcoRBddffIFrWsB/u4EXewXbimB8Pe650=
-----END RSA PRIVATE KEY-----';
    }

    public function encrypt($data)
    {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
        else
            echo '错误';

        return $data;
    }

    public function decrypt($data)
    {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
    //私钥加密
    public function encrypt1($data)
    {
        if (openssl_private_encrypt($data, $encrypted, $this->privkey))
            $data = base64_encode($encrypted);
        else
            echo '错误';

        return $data;
    }
    //公钥解密
    public function decrypt1($data)
    {
        if (openssl_public_decrypt(base64_decode($data), $decrypted, $this->pubkey))
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
    /*
     * Auther:langxi
     *
     * 公钥加密
     */
    public function encrypt_data($data)
    {

        $crypt_res = "";
        for ($i = 0; $i < ((strlen($data) - strlen($data) % 117) / 117 + 1); $i++) {
            $crypt_res = $crypt_res . ($this->encrypt(mb_strcut($data, $i * 117, 117, 'utf-8')));
        }
        return $crypt_res;
    }

    /**
     * Auther:langxi
     *
     * 私钥解密
     * @param $data
     * @return string
     */
    public function decrypt_data($data)
    {

        $decrypt_res = "";
        $datas = explode('=', $data);
        foreach ($datas as $value) {
            $decrypt_res = $decrypt_res . $this->decrypt($value);
        }
        return $decrypt_res;
    }

    /**
     * Auther:langxi
     *
     * 私钥加密
     * @param $privatekey
     * @param $data
     * @return string
     * @throws ErrorException
     */
    public function encrypt_data1($data)
    {
        $crypt_res = "";
        for ($i = 0; $i < ((strlen($data) - strlen($data) % 117) / 117 + 1); $i++) {
            $crypt_res = $crypt_res . ($this->encrypt1(mb_strcut($data, $i * 117, 117, 'utf-8')));
        }
        return $crypt_res;
    }

    /**
     * Auther:langxi
     *
     * 公钥解密
     * @param $publickey
     * @param $data
     * @return string
     */
    public function decrypt_data1($data)
    {
        $decrypt_res = "";
        $datas = explode('=', $data);
        foreach ($datas as $value) {
            $decrypt_res = $decrypt_res . $this->decrypt1($value);
        }
        return $decrypt_res;
    }

    /**
     * Auther:langxi
     *
     * 生成要请求给服务器的字符串
     * $para_temp 请求前的参数数组
     * return 要请求的参数数组
     */
    public function buildRequestPara($para_temp) {
        //除去数组中的空值参数
        $para_filter = $this->paraFilter($para_temp);
        //对参数数组排序
        $para_sort = $this->argSort($para_filter);
        //生成签名结果
        $para_sort = $this->buildRequestMysign($para_sort);
        return $para_sort;
    }

    /**
     * Auther:langxi
     *
     * 获取Apppost过来的字符串
     * $para_temp 请求前的参数数组
     * return 要请求的参数数组
     */
    public function buildLaiPara($para_sort) {
        $para_sort = $this->createLinkarray($para_sort);
        return $para_sort;
    }

    /**
     * 获取返回时的签名验证结果
     * $para_temp 通知返回来的参数数组
     * $sign 返回的签名结果
     * $isSort 是否对待签名数组排序
     * return 签名验证结果
     */
    function getSignVeryfy($para_temp, $sign, $isSort) {
        //除去待签名参数数组中的空值和签名参数

        /*$para = $this->paraFilter($para_temp);
        //对待签名参数数组排序
        if($isSort) {
            $para = $this->argSort($para);
        }else{
            return false;
        }

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para);*/

        $isSgin = false;
        $isSgin = $this->rsaVerify($sign);

        return $isSgin;
    }

    /**
     * Auther:langxi
     *
     * 生成签名结果
     * $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    private function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        return $prestr;
    }

    /**
     * Auther:langxi
     *
     * 除去数组中的空值
     * $para 参数组
     * return 去掉空值的参数组
     */
    private function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == 'sign' || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * Auther:langxi
     *
     * 对数组排序
     * $para 排序前的数组
     * return 排序后的数组
     */
    private function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * Auther:langxi
     *
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    private function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }

    /**
     * Auther:langxi
     *
     * 把字符串变为数组
     */
    private function createLinkarray($para){
        $data = array();
        $dat = explode('&',$para);
        foreach($dat as $key => $value){
            $da = explode('=',$value);
            foreach($da as $k => $v){
                $data[$v['0']] = $v['1'];
            }
        }

       return $data;
    }

    /**
     * RSA验签
     * $data 待签名数据
     * $sign 要校对的的签名结果
     * return 验证结果
     */
    /*function rsaVerify($data, $sign){
        $res = openssl_get_publickey($this->pubkey);
        $result = (bool)openssl_verify($data, $sign, $res);
        openssl_free_key($res);
        return $result;

    }*/
    function rsaVerify($sign)
    {
        $return = $this->decrypt_data($sign);
        var_dump($return);
        if($return){
            return true;
        }else{
            return false;
        }


    }

    /**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取处理结果
     * $para_temp 请求参数数组
     * return 处理结果
     */
   /* function buildRequestHttp($para_temp) {
        $sResult = '';

        //待请求参数数组字符串
        $request_data = $this->buildRequestPara($para_temp);

        //远程获取数据
        $sResult = $this->getHttpResponsePOST('localhost/mmnoey', 'mmnoey',$request_data);

        return $sResult;
    }*/

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * $url 指定URL完整路径地址
     * $cacert_url 指定当前工作目录绝对路径
     * $para 请求的数据
     * return 远程输出的数据
     */
    /*function getHttpResponsePOST($url, $cacert_url, $para) {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }*/



}