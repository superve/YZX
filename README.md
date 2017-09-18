# 该项目主要是云之讯短信验证接口使用

## 云之讯短信验证接口

# 示例代码
```php
	require 'vendor/autoload.php';

	$config = [
		'accountSid' => 'f82sfdsdf815f79sdf85745c5b50b0bb223e8' ,
		'token' => 'f298b645sfsd748dfa4csdf349b0a23fb722',
		'appId' => 'e0406dsdfdssdfa7b40a28f8d114ec12c932b'
	];
	$client = new Yzx($config);

	$phoneNumber = '13672431596'; // 手机号
	$param = '123'; // 验证码
	$data = $client->sendRegisterMessage($phoneNumber, $param);

```
