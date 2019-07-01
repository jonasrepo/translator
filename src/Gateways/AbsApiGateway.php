<?php
/**
 * AbsApiGateway.php
 *
 * Author: Guo
 * Email jonasyeah@163.com
 *
 * Date:   2019-06-29 16:54
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Gateways;


/**
 * Class AbsApiGateway
 *
 * @package Jonas\Translator\Gateways
 */
abstract class AbsApiGateway extends AbsGateway
{
    /**
     * AbsApiGateway constructor.
     *
     * @param       $gateway
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct($gateway, array $config = [])
    {
        parent::__construct($gateway, $config);
        if(!$this->config->get('app_id') || !$this->config->get('app_sec'))
        {
            if(!$this->config->get('app_id')){
                throw new \Exception('确实必要的参数: app_id or app_sec');
            }
        }
    }

}