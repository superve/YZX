<?php
namespace Yzx;

/**
 * 示例代码：
 	
 	$config = [
		'accountSid' => 'f82sfdsdf815f7985745c5b50b0bb223e8' ,
		'token' => 'f298b645sfsd748dfa4c349b0a23fb722',
		'appId' => 'e0406dsdfdsa7b40a28f8d114ec12c932b'
	];
	$client = new Yzx($config);

	$phoneNumber = '13672431596'; // 手机号
	$param = '123'; // 验证码
	$data = $client->sendRegisterMessage($phoneNumber, $param);

 */

class Yzx{
	private $SoftVersion = '2014-06-30';
	private $accountSid = ''; // 配置为自己的AK
	private $token = ''; // 配置为自己的SK
	private $appId = ''; // 配置为自己的应用ID
	private $templateId = ''; // 短信模板ID


	private $header = null;
	private $callback = '';
	private $restful_url = '';

	function __construct($config = ['accountSid' => '', 'token' => '', 'appId' => '']){

		$this->accountSid = $config['accountSid'];
		$this->token = $config['token'];
		$this->appId = $config['appId'];
		$this->templateId = $config['templateId'];

		date_default_timezone_set("PRC");
		$this->datetime = date('YmdHis');
		$this->sig = strtoupper(md5($this->accountSid.$this->token.$this->datetime));
		
		$this->restful_url = 'https://api.ucpaas.com/'. $this->SoftVersion .'/Accounts/' . $this->accountSid . '/Messages/templateSMS?sig=' . $this->sig;

		$this->header = [
			'Accept:application/json',
			'Content-Type:application/json;charset=utf-8',
			'Authorization:'. base64_encode($this->accountSid . ':' . $this->datetime),
		];
	}
	
	/**
	 * 作用：处理并返回调用接口的结果（json格式）
	 * @param string $url 接口URL
	 * @param array $data 接口规定要传递的数据
	 * @return obj 数据对象
	 */
	private function json_curl($url,$data){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_URL, $url);
		if(strtolower(substr($url,0,5)) == 'https'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	//信任任何证书;
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	//检查证书中是否设置域名,0不验证;
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$output = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($status != 200){
			$output = (object)array(
				'status' => $status,
				'errno' => curl_errno($ch),
				'error' => curl_error($ch)
			);
		}else{
			$output = json_decode($output, true);
		}
		curl_close($ch);
		return $output;
	}

	/**
	 * 发送短信验证的接口
	 * @param  string $phoneNumber 注册用户手机号
	 * @param  string $param       需要传递给短信验证模板的参数，多个参数使用逗号分隔，默认参数为'用户名,验证码,有效期单位为分钟'
	 * @return mixed               提示信息
	 */	
	public function sendRegisterMessage($phoneNumber, $param = '')
	{
		$data = [
			'templateSMS'=>[
				'appId'=> $this->appId,
				'templateId'=> $this->templateId,
				'to'=> $phoneNumber,
				'param'=> $param,
			],
		];
		return $this->json_curl($this->restful_url, $data);

	}	
}