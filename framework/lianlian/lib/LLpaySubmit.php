<?php
namespace framework\lianlian\lib;
require_once ("llpay_core.function.php");
require_once ("llpay_md5.function.php");
require_once ("llpay_rsa.function.php");

class LLpaySubmit {

	var $llpay_config;
	/**
	 *连连支付网关地址
	 */
	var $llpay_gateway_new = 'https://yintong.com.cn/payment/authpay.htm';

	function __construct($llpay_config) {
		$this->llpay_config = $llpay_config;
	}
	function LLpaySubmit($llpay_config) {
		$this->__construct($llpay_config);
	}

	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = createLinkstring($para_sort);
		$mysign = "";
		switch (strtoupper(trim($this->llpay_config['sign_type']))) {
			case "MD5" :
				$mysign = md5Sign($prestr, $this->llpay_config['key']);
				break;
			case "RSA" :
				$mysign = RsaSign($prestr);
				break;
			default :
				$mysign = "";
		}
		//file_put_contents("log.txt","签名:".$mysign."\n", FILE_APPEND);
		return $mysign;
	}

	/**
	 * 生成要请求给连连支付的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组
	 */
	function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);
		//对待签名参数数组排序
		$para_sort = argSort($para_filter);
		//生成签名结果

		$mysign = $this->buildRequestMysign($para_sort);

		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->llpay_config['sign_type']));
		foreach ($para_sort as $key => $value) {
			$para_sort[$key] = $value;
		}
		return $para_sort;
		//return urldecode(json_encode($para_sort));
	}

	/**
	 * 生成要请求给连连支付的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组字符串
	 */
	function buildRequestParaToString($para_temp) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);

		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
		$request_data = createLinkstringUrlencode($para);

		return $request_data;
	}

	/**
	 * 建立请求，以表单HTML形式构造（默认）
	 * @param $para_temp 请求参数数组
	 * @param $method 提交方式。两个值可选：post、get
	 * @param $button_name 确认按钮显示文字
	 * @return 提交表单HTML文本
	 */
	public function buildRequestForm($para_temp, $method, $button_name) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);

        //风控值去斜杠
        $para['risk_item'] =stripslashes( $para['risk_item']);

		$sHtml = "<meta http-equiv='content-type' content='text/html;charset=UTF-8'>";
		$sHtml .= "<form id='llpaysubmit' name='llpaysubmit' action='" . $this->llpay_gateway_new . "' method='" . $method . "'>";
		$sHtml .= "<input type='hidden' name='version' value='" . $para['version'] . "'/>";
		$sHtml .= "<input type='hidden' name='oid_partner' value='" . $para['oid_partner'] . "'/>";
		$sHtml .= "<input type='hidden' name='user_id' value='" . $para['user_id'] . "'/>";
		$sHtml .= "<input type='hidden' name='timestamp' value='" . $para['timestamp'] . "'/>";
		$sHtml .= "<input type='hidden' name='sign_type' value='" . $para['sign_type'] . "'/>";
		$sHtml .= "<input type='hidden' name='sign' value='" . $para['sign'] . "'/>";
		$sHtml .= "<input type='hidden' name='busi_partner' value='" . $para['busi_partner'] . "'/>";
		$sHtml .= "<input type='hidden' name='no_order' value='" . $para['no_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='dt_order' value='" . $para['dt_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='name_goods' value='" . $para['name_goods'] . "'/>";
//		$sHtml .= "<input type='hidden' name='info_order' value='" . $para['info_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='money_order' value='" . $para['money_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='notify_url' value='" . $para['notify_url'] . "'/>";
		$sHtml .= "<input type='hidden' name='url_return' value='" . $para['url_return'] . "'/>";
		$sHtml .= "<input type='hidden' name='userreq_ip' value='" . $para['userreq_ip'] . "'/>";
//		$sHtml .= "<input type='hidden' name='url_order' value='" . $para['url_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='valid_order' value='" . $para['valid_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='bank_code' value='" . $para['bank_code'] . "'/>";
//		$sHtml .= "<input type='hidden' name='pay_type' value='" . $para['pay_type'] . "'/>";
//		$sHtml .= "<input type='hidden' name='no_agree' value='" . $para['no_agree'] . "'/>";
//		$sHtml .= "<input type='hidden' name='shareing_data' value='" . $para['shareing_data'] . "'/>";
		$sHtml .= "<input type='hidden' name='risk_item' value='" . $para['risk_item'] . "'/>";
		$sHtml .= "<input type='hidden' name='id_type' value='" . $para['id_type'] . "'/>";
		$sHtml .= "<input type='hidden' name='id_no' value='" . $para['id_no'] . "'/>";
		$sHtml .= "<input type='hidden' name='acct_name' value='" . $para['acct_name'] . "'/>";
//		$sHtml .= "<input type='hidden' name='flag_modify' value='" . $para['flag_modify'] . "'/>";
		$sHtml .= "<input type='hidden' name='card_no' value='" . $para['card_no'] . "'/>";
//		$sHtml .= "<input type='hidden' name='back_url' value='" . $para['back_url'] . "'/>";
		//submit按钮控件请不要含有name属性
		$sHtml = $sHtml . "<input type='submit' value='" . $button_name . "'></form>";
		$sHtml = $sHtml."<script>document.forms['llpaysubmit'].submit();</script>";
		return $sHtml;
	}

	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果
	 * @param $para_temp 请求参数数组
	 * @return 连连支付处理结果
	 */
	function buildRequestHttp($para_temp) {
		$sResult = '';

		//待请求参数数组字符串
		$request_data = $this->buildRequestPara($para_temp);

		//远程获取数据
		$sResult = getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $request_data, trim(strtolower($this->llpay_config['input_charset'])));

		return $sResult;
	}

	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果，带文件上传功能
	 * @param $para_temp 请求参数数组
	 * @param $file_para_name 文件类型的参数名
	 * @param $file_name 文件完整绝对路径
	 * @return 连连支付返回处理结果
	 */
	function buildRequestHttpInFile($para_temp, $file_para_name, $file_name) {

		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		$para[$file_para_name] = "@" . $file_name;

		//远程获取数据
		$sResult = getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $para, trim(strtolower($this->llpay_config['input_charset'])));

		return $sResult;
	}

	/**
	 * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
	 * return 时间戳字符串
	 */
	function query_timestamp() {
		$url = $this->llpay_gateway_new . "service=query_timestamp&partner=" . trim(strtolower($this->llpay_config['partner'])) . "&_input_charset=" . trim(strtolower($this->llpay_config['input_charset']));
		$encrypt_key = "";

		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

		return $encrypt_key;
	}
}
?>