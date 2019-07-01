<?php
/**
 * BaiduApi.php
 *
 * Author: Guo
 *
 * Date:   2019-06-29 16:08
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Gateways;


use Jonas\Translator\Exceptions\GatewayErrorException;
use Jonas\Translator\Result;

class BaiduApi extends AbsGateway
{
    /**
     * endpoint
     */
    const ENDPOINT = 'http://api.fanyi.baidu.com/api/trans/vip/translate';

    /**
     * @param $string
     *
     * @return Result
     * @throws GatewayErrorException
     */
    function translate($string)
    {
        $args = [
            'q' => $string,
            'from' => 'auto',
            'to' => $this->autoChooseToLag($string),
            'appid' => $this->config->get('app_id'),
            'salt' => rand(10000,99999),
        ];

        $args['sign'] = $this->buildSign($string, $this->config->get('app_id'), $args['salt'], $this->config->get('app_sec'));
        $res = $this->get(self::ENDPOINT, $args);

        if(isset($res['error_code'])) {
            throw new GatewayErrorException($res['error_code'], $res['error_msg'], $res);
        }
        return new Result($res['trans_result'][0]['dst'], [], $res);
    }

    /**
     * build the sign for query
     * @param $query
     * @param $appID
     * @param $salt
     * @param $secKey
     *
     * @return string
     */
    protected function buildSign($query, $appID, $salt, $secKey)
    {
        $str = $appID . $query . $salt . $secKey;
        $ret = md5($str);
        return $ret;
    }
}