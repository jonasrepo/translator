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


use Jonas\Translator\Contracts\GatewayInterface;
use Jonas\Translator\Lng;
use Jonas\Translator\Support\Config;
use Jonas\Translator\Traits\HasHttpRequest;

/**
 * Class AbsGateway
 *
 * @package Jonas\Translator\Gateways
 */
abstract class AbsGateway implements GatewayInterface
{
    use HasHttpRequest;

    /**
     * default api request timeout
     */
    const DEFAULT_TIMEOUT = 5;
    /**
     * lng auto string
     */
    const AUTO_STRING     = 'auto';
    /**
     * if support to set the target lng to auto
     * @var bool
     */
    protected $support_to_lng_auto = true;
    /**
     * to lng
     * @var
     */
    protected $to;
    /**
     * @var Config
     */
    protected $config;

    /**
     * gateway name
     * @var
     */
    protected $gateway;

    /**
     * AbsGateway constructor.
     *
     * @param array $config
     */
    public function __construct($gateway, $config = [])
    {
        $this->gateway = $gateway;
        $this->config = new Config($config);
    }

    /**
     * @param $to
     *
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTo()
    {
        return $this->to;
    }

    /**
     * auto choose target lng
     * @param $str
     *
     * @return mixed|string
     */
    protected function autoChooseToLag($str)
    {
        if($this->getTo()) {
            return $this->getTo();
        }
        //if the api donot support to choose the target lng
        if($this->support_to_lng_auto) {
            return self::AUTO_STRING;
        }else {
            //if the from lng is auto then smart chooose target lng
            $method = 'convertTo' . ucwords($this->gateway);
            if(is_mb_string($str)) {
                return Lng::$method(Lng::ENGLISH);
            }else {
                return  Lng::$method(Lng::CHINESE);
            }
        }
    }

    /**
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

}