<h1 align="center">Translator</h1>
<p align="center">该翻译库整合了有道和百度的翻译</p>

## 特点
- 支持有道和百度基于api的通用翻译接口
- 通过破解有道与百度翻译页面接口, 可以免注册直接使用(由于抓取的数据格式可能会被调整, 如果要在正式环境使用, 最好去服务商平台注册使用api)
- 统一调用与数据返回

## 环境需求

- PHP >= 5.6

## 安装

```shell
$ composer require "jonasyeah/translator"
```

## 使用
```php
use Jonas\Translator\Translator;
use Jonas\Translator\Lng;

$config = [
	 	// HTTP 请求的超时时间（秒）, 如果不设置, 系统默认使用5秒
        'timeout' => 5.0,

        // 默认发送配置, 如果不设置, 系统默认使用有道
        'default' => [
            // 默认可用的发送网关,
            'gateways' => [
                'youdao',
            ],
        ],
        // 可用的网关配置
        //如果没有设置qpp_key 和app_sec, 系统默认使用页面抓取活的接口
        'gateways' => [
            'baidu' => [
                'app_key' => '123xxxxx',
                'app_sec' => '123xxxxx',
            ],
            'baidu' => [
				'app_key' => '123xxxxx',
				'app_sec' => '123xxxxx',
            ],
        ],
]
$translator = new Translator($config);

//使用百度翻译
$res = $translator->baidu('天气不错');
//使用有道翻译
$res = $translator->youdao('天气不错');
//可以指定被翻译成的语言
$res = $translator->to(Lng::English)->baidu('天气不错');

//使用有道和百度翻译
$res = $translator->translate('天气不错', [Translator::Youdao, Translator::Baidu]);

```
### 关于成功时返回值
```php
//使用单一翻译
$res = $translator->baidu('天气不错');
//格式
[
	'gateway' => $gateway,
	'status' => self::STATUS_SUCCESS,
	'result' => ResultInstance,
];
//使用多种翻译
[
	"baidu"	=> [
		'gateway' => 'baidu',
		'status' => self::STATUS_SUCCESS, //状态
		'result' => ResultInstance,
    ],
    "youdao"	=> [
		'gateway' => 'youdao',
		'status' => self::STATUS_SUCCESS, //状态
		'result' => ResultInstance,
    ],
]
//ResultInstance 获取结果
//翻译结果
$result->getTransResult();
//词典结果, 如果翻译的是某个词
$result->getDictResult();
//原始结果
$result->getRaw()
```

### 失败时的返回值
```php
//格式
[
	'gateway' => $gateway,
	'status' => self::STATUS_FAILURE,
	'exception' => $e,
];
//如果要查看原始异常
$e->raw

```
### 异常
* 网关数据异常 GatewayErrorException.php
* 网关请求异常 HttpErrorException.php
* 无效网关异常 InvalidGatewayException.php


## 代码贡献
由于测试及使用环境的限制，本项目中只开发了「有道」和「百度」平台的翻译。

如果您有其它支付网关的需求，或者发现本项目中需要改进的代码，**_欢迎 Fork 并提交 PR！_**

## LICENSE
MIT