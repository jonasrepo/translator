<?php
/**
 * YoudaoCrawl.phpl.php
 *
 * Author: Guo
 *
 * Date:   2019-06-28 13:14
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Gateways;


use Jonas\Translator\Exceptions\GatewayErrorException;
use Jonas\Translator\Result;

/**
 * Class YoudaoCrawl
 *
 * @package Jonas\Translator\Gateways
 */
class YoudaoCrawl extends AbsGateway
{
    /**
     * endpoint
     */
    const ENDPOINT    = 'http://fanyi.youdao.com/translate_o';
    /**
     * auto string
     */
    const AUTO_STRING = 'AUTO';

    /**
     * @param $str
     *
     * @return Result
     * @throws GatewayErrorException
     */
    public function translate($str)
    {
        $salt = (int) microtime(true) * 1000;
        $sign =$this->generateSign($salt, $str);
        $res = $this->post(self::ENDPOINT, [
            "i" => $str,
            "from" => "AUTO",
            "to" => $this->autoChooseToLag($str),
            "smartresult" => "dict",
            "client" => "fanyideskweb",
            "salt" => $salt,
            "sign" => $sign,
            "ts" => time(),
            "bv" => "b987d96b309bc8cb06d5964e4adb87df",
            "doctype" => "json",
            "version" => "2.1",
            "keyfrom" => "fanyi.web",
            "action" => "FY_BY_CLICKBUTTION",
        ], [
            'Referer'=> 'http://fanyi.youdao.com/',
            'Cookie' =>'OUTFOX_SEARCH_USER_ID="805698409@50.169.0.83";'
        ]);
       if($res['errorCode'] !== 0) {
            Throw new GatewayErrorException('', $res['errorCode'], $res);
       }
        $dict_result = isset($res['smartResult']['entries']) ? array_values(array_filter($res['smartResult']['entries'])) : [];
       return new Result($res['translateResult']['0']['0']['tgt'], $dict_result, $res);
    }

    /**
     * @param $salt
     * @param $str
     *
     * @return string
     */
    protected function generateSign($salt, $str)
    {
        return md5('fanyideskweb' . $str . $salt .'97_3(jkMYg@T[KZQmqjTK');
    }
}