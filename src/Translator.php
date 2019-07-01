<?php
/**
 * Translator.php
 *
 * Author: Guo
 *
 * Date:   2019-06-27 16:47
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator;


use Jonas\Translator\Contracts\GatewayInterface;
use Jonas\Translator\Exceptions\InvalidGatewayException;
use Jonas\Translator\Support\Config;

/**
 * Class Translator
 *
 * @package Jonas\Translator
 */
class Translator
{
    /**
     * youdao gateway
     */
    const YOUDAO = 'youdao';
    /**
     * baidu gateway
     */
    const BAIDU = 'baidu';
    /**
     * @var array
     */
    protected $gateways = [];
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var
     */
    protected $to;
    /**
     * @var
     */
    protected $toraw;
    /**
     * @var
     */
    protected $message;

    protected $default_gateway = self::YOUDAO;

    /**
     * Translator constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * @param $gateway
     * @param $args
     *
     * @return array|mixed
     */
    public  function __call($gateway, $args)
    {
        return $this->getMessage()->translate($args[0],[$gateway]);
    }

    /**
     * @param       $str
     * @param array $gateways
     *
     * @return array|mixed
     */
    public function translate($str, $gateways = [])
    {
        if(!$gateways) {
            $gateways = [$this->getDefaultGateway()];
        }

        return $this->getMessage()->translate($str, $gateways);
    }

    /**
     * set target lng
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
     * set target lng by raw str
     * @param $to
     *
     * @return $this
     */
    public function toRaw($to){
        $this->toraw = $to;
        return $this;
    }

    /**
     * @return Config
     */
    public function getTo($gateway)
    {
        $to = '';
        if($this->to) {
            $convert_method = 'convertTo' . ucwords($gateway);
            $to = Lng::$convert_method($this->to);
        }
        if($this->toraw) {
            $to = $this->toraw;
        }
        return $to;
    }

    /**
     * @param $gateway
     *
     * @return mixed
     */
    protected function getConfig($gateway)
    {
        return $this->config->get('gateways.'.$gateway, []);
    }

    /**
     * @param $gateway
     *
     * @return mixed
     * @throws InvalidGatewayException
     */
    public function gateway($gateway)
    {
        if($config = $this->getConfig($gateway)) {
            $gateway_class_suffix = 'Api';
        }else {
            $gateway_class_suffix = 'Crawl';
        }
        $gateway_class = __NAMESPACE__ . "\\Gateways\\" . ucwords($gateway) . $gateway_class_suffix;
        if(!class_exists($gateway_class)) {
            throw new InvalidGatewayException("Gateway [{$gateway}] Not Exists");
        }

        if(!is_subclass_of($gateway_class, GatewayInterface::class)) {
            throw new InvalidGatewayException("Gateway [$gateway] Must Be An Instance Of GatewayInterface");
        }

        if(!isset($this->gateways[$gateway_class])) {
            $config['timeout'] = $this->config->get('timeout');
            $config['errorlog'] = $this->config->get('gateways.errorlog.file');
            $this->gateways[$gateway_class] = new $gateway_class($gateway, $config);
        }

        return $this->gateways[$gateway_class];

    }

    /**
     * default gateway
     * @return mixed
     */
    private function getDefaultGateway()
    {
        return $this->config->get('default.strategy', $this->default_gateway);
    }

    /**
     * @return TranslateManager
     */
    private function getMessage()
    {
        return $this->message ? $this->message : new TranslateManager($this);
    }

}