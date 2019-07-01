<?php
/**
 * YoudaoApi.php
 *
 * Author: Guo
 * Email jonasyeah@163.com
 *
 * Date:   2019-06-29 16:53
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Gateways;


use Jonas\Translator\Exceptions\GatewayErrorException;
use Jonas\Translator\Result;

/**
 * Class YoudaoApi
 *
 * @package Jonas\Translator\Gateways
 */
class YoudaoApi extends AbsApiGateway
{
    /**
     * endpoint
     */
    const ENDPOINT = 'http://openapi.youdao.com/api';

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
            'appKey' => $this->config->get('app_id'),
            'salt' => rand(10000,99999),
            'signType' => 'v3',
            'curtime' => time(),
        ];
        $args['sign'] = $this->buildSign($args['appKey'], $args['q'], $args['salt'], $args['curtime'], $this->config->get('app_sec'));

        $res = $this->post(self::ENDPOINT, $args);

        if($res['errorCode'] !== '0')
        {
            throw new GatewayErrorException($res['l'], $res['errorCode'], $res);
        }
        $dict_res = isset($res['web'][0]['value']) ? $res['web'][0]['value'] : [];
        return new Result(implode(',', $res['translation']), $dict_res, $res);
    }


    /**
     * @param $appKey
     * @param $q
     * @param $salt
     * @param $curtime
     * @param $app_sec
     *
     * @return string
     */
    private function buildSign($appKey, $q, $salt, $curtime, $app_sec)
    {
        if(mb_strlen($q) > 20) {
            $q = mb_substr($q, 0, 10) . mb_strlen($q) . mb_substr($q, -10, 10);
        }
        return hash('sha256', $appKey. $q .$salt . $curtime. $app_sec);
    }


}